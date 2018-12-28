<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Fixtures;

use Prooph\EventStore\Exception\RuntimeException;
use Prooph\Fixtures\Cleaner\Cleaner;
use Prooph\Fixtures\Fixture\DependentFixture;
use Prooph\Fixtures\Fixture\Fixture;
use Prooph\Fixtures\Locator\FixturesLocator;

final class FixturesManager
{
    /**
     * @var FixturesLocator
     */
    private $fixturesLocator;

    /**
     * @var Cleaner
     */
    private $cleaner;

    /**
     * Creates a new fixtures manager.
     *
     * @param FixturesLocator $fixturesLocator
     * @param Cleaner $cleaner
     */
    public function __construct(FixturesLocator $fixturesLocator, Cleaner $cleaner)
    {
        $this->fixturesLocator = $fixturesLocator;
        $this->cleaner = $cleaner;
    }

    /**
     * Cleans up the database.
     *
     * @return void
     */
    public function cleanUp(): void
    {
        ($this->cleaner)();
    }

    /**
     * Gets the fixtures to load.
     *
     * @return Fixture[]
     */
    public function getFixtures(): iterable
    {
        $orderedFixtures = $this->orderFixturesByDependencies();

        return $orderedFixtures;
    }

    private function orderFixturesByDependencies(): array
    {
        $orderedFixtures = [];
        $unorderedFixtures = $this->fixturesLocator->getFixtures();
        \ksort($unorderedFixtures, SORT_NATURAL);

        // Put all fixtures without dependencies in front
        foreach ($unorderedFixtures as $id => $fixture) {
            if (! $fixture instanceof DependentFixture) {
                $orderedFixtures[] = $fixture;
                unset($unorderedFixtures[$id]);
            } else {
                $this->assertThatDependenciesAreLoaded($fixture);
            }
        }

        $order = \count($orderedFixtures) - 1;
        $previousCount = null;
        // As long as there is unordered fixtures and that it was able to order at least one
        while (0 < ($count = \count($unorderedFixtures)) && $count !== $previousCount) {
            $previousCount = $count;

            foreach ($unorderedFixtures as $id => $fixture) {
                $allDependenciesAreAlreadyOrdered = ! \array_intersect(
                    $fixture::getDependencies(),
                    \array_keys($unorderedFixtures)
                );

                if ($allDependenciesAreAlreadyOrdered) {
                    $orderedFixtures[++$order] = $fixture;
                    unset($unorderedFixtures[$id]);
                }
            }
        }

        if (0 !== $count) { // Break the loop without all fixtures ordered => circular references
            throw new RuntimeException(\sprintf(
                'Circular dependencies detected! Checks the dependencies for: %s',
                \implode(', ', \array_keys($unorderedFixtures))
            ));
        }

        return $orderedFixtures;
    }

    /**
     * Asserts that the dependencies of a fixture are loaded.
     *
     * @param DependentFixture $fixture
     *
     * @return void
     */
    private function assertThatDependenciesAreLoaded(DependentFixture $fixture): void
    {
        foreach ($fixture::getDependencies() as $dependencyFqn) {
            if (\get_class($fixture) === $dependencyFqn) {
                throw new RuntimeException(\sprintf(
                    'The fixture "%s" defines itself as a dependency.',
                    $dependencyFqn
                ));
            }

            if (! $this->fixturesLocator->has($dependencyFqn)) {
                throw new RuntimeException(\sprintf(
                    'The dependency "%s" of the fixture "%s" is not loaded.'
                    . ' Please check that it is proprely configured.',
                    $dependencyFqn,
                    \get_class($fixture)
                ));
            }
        }
    }
}
