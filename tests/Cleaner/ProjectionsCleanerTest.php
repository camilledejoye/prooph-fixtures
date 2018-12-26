<?php

/**
 * This file is part of elythy/prooph-fixtures.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Tests\Cleaner;

use PHPUnit\Framework\TestCase;
use Prooph\EventStore\Exception\RuntimeException;
use Prooph\EventStore\Projection\ProjectionManager;
use Prooph\Fixtures\Cleaner\ProjectionsCleaner;
use Psr\Container\ContainerInterface;

class ProjectionsCleanerTest extends TestCase
{
    const BATCH_SIZE = 2;
    const APP_MANAGER_NAME = 'app_manager';

    /**
     * @var string[]
     */
    private $projectionNames;

    /**
     * @var ContainerInterface
     */
    private $managers;

    /**
     * @var ProjectionsCleaner
     */
    private $sut;

    /**
     * @var mixed
     */
    private $projectionManagersLocator;

    protected function setUp()
    {
        $this->projectionNames = [
            self::APP_MANAGER_NAME => [
                'user_projection',
                'todo_projection',
            ],
        ];

        $this->managers = [
            self::APP_MANAGER_NAME => $this->createAProjectionManager(),
        ];

        $this->projectionManagersLocator = $this->createProjectionManagersLocator($this->managers);

        $this->sut = new ProjectionsCleaner(
            $this->projectionManagersLocator,
            $this->managers,
            self::BATCH_SIZE
        );
    }

    /**
     * @test
     */
    public function it_throws_an_exception_with_an_invalid_manager_name()
    {
        $invalidManagerName = 'not_a_valid_manager_name';
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Projection manager "not_a_valid_manager_name" not found.');

        $sut = new ProjectionsCleaner(
            $this->projectionManagersLocator,
            [$invalidManagerName => ''],
            self::BATCH_SIZE
        );
        $sut();
    }

    /**
     * @test
     */
    public function it_cleans_all_registered_projections()
    {
        $this->assertThatAllProjectionNamesAreRetrievedFromTheAppManager();
        $this->assertThatAllProjectionsAreDeletedFromTheAppManager();

        ($this->sut)();
    }

    private function createProjectionManagersLocator(array $managers): ContainerInterface
    {
        $locator = new class($managers) implements ContainerInterface {
            /**
             * @var ProjectionManager[]
             */
            private $managers;

            public function __construct(array $managers)
            {
                $this->managers = $managers;
            }

            public function get($id)
            {
                return $this->managers[$id] ?? null;
            }

            public function has($id)
            {
                return isset($this->managers[$id]);
            }
        };

        return $locator;
    }

    private function createAProjectionManager(): ProjectionManager
    {
        return $this->getMockForAbstractClass(ProjectionManager::class);
    }

    private function assertThatAllProjectionNamesAreRetrievedFromTheAppManager(): void
    {
        $projectionNames = $this->projectionNames[self::APP_MANAGER_NAME];

        $this->managers[self::APP_MANAGER_NAME]
            ->expects($this->exactly(2))
            ->method('fetchProjectionNames')
            ->withConsecutive(
                [$this->equalTo(null), $this->equalTo(self::BATCH_SIZE), $this->equalTo(0)],
                [$this->equalTo(null), $this->equalTo(self::BATCH_SIZE), $this->equalTo(self::BATCH_SIZE)]
            )
            ->willReturnOnConsecutiveCalls(
                $projectionNames,
                []
            );
    }

    private function assertThatAllProjectionsAreDeletedFromTheAppManager(): void
    {
        $projectionNames = $this->projectionNames[self::APP_MANAGER_NAME];

        $this->managers[self::APP_MANAGER_NAME]
            ->expects($this->exactly(2))
            ->method('resetProjection')
            ->withConsecutive(
                [$projectionNames[0]],
                [$projectionNames[1]]
            );
    }
}
