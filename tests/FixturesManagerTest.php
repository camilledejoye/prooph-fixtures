<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prooph\EventStore\Exception\RuntimeException;
use Prooph\Fixtures\Cleaner\Cleaner;
use Prooph\Fixtures\FixturesManager;
use Prooph\Fixtures\Locator\FixturesLocator;
use Prooph\Fixtures\Tests\Fixtures\ArticlesCircularReferenceFixture;
use Prooph\Fixtures\Tests\Fixtures\ArticlesFixture;
use Prooph\Fixtures\Tests\Fixtures\CommentsCircularReferenceFixture;
use Prooph\Fixtures\Tests\Fixtures\CommentsFixture;
use Prooph\Fixtures\Tests\Fixtures\SelfDependentFixture;
use Prooph\Fixtures\Tests\Fixtures\TagsFixture;
use Prooph\Fixtures\Tests\Fixtures\UsersCircularReferenceFixture;
use Prooph\Fixtures\Tests\Fixtures\UsersFixture;

class FixturesManagerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $fixturesLocator;

    /**
     * @var MockObject
     */
    private $cleaner;

    /**
     * @var FixturesManager
     */
    private $sut;

    protected function setUp()
    {
        $this->fixturesLocator = $this->getMockForAbstractClass(FixturesLocator::class);
        $this->cleaner = $this->getMockForAbstractClass(Cleaner::class);

        $this->sut = new FixturesManager(
            $this->fixturesLocator,
            $this->cleaner
        );
    }

    /**
     * @test
     */
    public function it_cleans_up_everything()
    {
        $this->cleaner
            ->expects($this->once())
            ->method('__invoke');

        $this->sut->cleanUp();
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_a_fixture_depends_on_itself()
    {
        $this->defineLoadedFixtures([new SelfDependentFixture()]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(\sprintf(
            'The fixture "%s" defines itself as a dependency.',
            SelfDependentFixture::class
        ));

        $this->sut->getFixtures();
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_a_dependency_is_not_loaded()
    {
        $this->defineLoadedFixtures([
            new CommentsFixture(),
            new UsersFixture(),
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(\sprintf(
            'The dependency "%s" of the fixture "%s" is not loaded.'
            . ' Please check that it is proprely configured.',
            ArticlesFixture::class,
            CommentsFixture::class
        ));

        $this->sut->getFixtures();
    }

    /**
     * @test
     */
    public function it_stops_when_encounter_a_case_of_circular_references()
    {
        $this->defineLoadedFixtures([
            new CommentsCircularReferenceFixture(),
            new UsersCircularReferenceFixture(),
            new ArticlesCircularReferenceFixture(),
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Circular dependencies detected!');

        $this->sut->getFixtures();
    }

    /**
     * @test
     */
    public function it_gets_all_the_fixtures_in_order()
    {
        $this->defineLoadedFixtures([
            new CommentsFixture(),
            new UsersFixture(),
            new ArticlesFixture(),
            new TagsFixture(),
        ]);

        $this->assertEquals(
            [
                new TagsFixture(),
                new UsersFixture(),
                new ArticlesFixture(),
                new CommentsFixture(),
            ],
            $this->sut->getFixtures()
        );
    }

    private function defineLoadedFixtures(array $fixtures)
    {
        $fixturesFqn = \array_map('get_class', $fixtures);
        $fixturesMap = \array_combine($fixturesFqn, $fixtures);

        $this->fixturesLocator
            ->expects($this->any())
            ->method('getFixtures')
            ->willReturn($fixturesMap);

        $this->fixturesLocator
            ->expects($this->any())
            ->method('has')
            ->willReturnCallback(function (string $parameter) use ($fixturesMap) {
                return isset($fixturesMap[$parameter]);
            });

        $this->fixturesLocator
            ->expects($this->any())
            ->method('get')
            ->willReturnCallback(function (string $parameter) use ($fixturesMap) {
                return $fixturesMap[$parameter] ?? null;
            });
    }
}
