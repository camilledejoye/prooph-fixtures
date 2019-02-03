<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Tests\Provider;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prooph\Fixtures\Fixture\Fixture;
use Prooph\Fixtures\Provider\InMemoryFixturesProvider;

class InMemoryFixturesProviderTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideFixtures
     */
    public function it_creates_a_fixtures_provider(array $fixtures)
    {
        $fixturesProvider = new InMemoryFixturesProvider($fixtures);

        foreach ($fixtures as $fixture) {
            $this->assertSame($fixture, $fixturesProvider->get(\get_class($fixture)));
        }
    }

    /**
     * @test
     * @dataProvider provideFixtures
     */
    public function it_provides_all_fixtures(array $fixtures)
    {
        $fixturesProvider = new InMemoryFixturesProvider($fixtures);

        $this->assertSame($fixtures, $fixturesProvider->all());
    }

    private function createAFixture(string $className): MockObject
    {
        return $this->getMockBuilder(Fixture::class)
            ->setMockClassName($className)
            ->getMockForAbstractClass();
    }

    public function provideFixtures(): array
    {
        $fixtures = [
            $this->createAFixture('AFixture'),
            $this->createAFixture('AnotherFixture'),
            $this->createAFixture('YetAnotherFixture'),
        ];

        $prepareFixtures = function (array $unpreparedFixtures) {
            $preparedFixtures = [];
            foreach ($unpreparedFixtures as $fixture) {
                $preparedFixtures[\get_class($fixture)] = $fixture;
            }

            return $preparedFixtures;
        };

        return [
            '3 ordinary fixtures' => [$prepareFixtures($fixtures)],
        ];
    }
}
