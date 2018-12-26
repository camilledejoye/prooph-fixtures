<?php

/**
 * This file is part of elythy/prooph-fixtures.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Locator;

use Prooph\Fixtures\Fixture\Fixture;

/**
 * @method Prooph\Fixtures\Fixture get($id)
 */
abstract class AbstractFixturesLocator implements FixturesLocator
{
    /**
     * @var Fixture[]
     */
    private $fixtures;

    /**
     * Create a new fixtures locator.
     *
     * @param Fixture[] $fixtures
     */
    public function __construct(iterable $fixtures)
    {
        $this->fixtures = [];

        $this->addFixtures($fixtures);
    }

    /**
     * {@inheritdoc}
     *
     * @return Fixture|null
     */
    public function get($id)
    {
        return $this->fixtures[$id] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return isset($this->fixtures[$id]);
    }

    /**
     * {@inheritdoc}
     */
    public function getFixtures(): array
    {
        return $this->fixtures;
    }

    /**
     * Adds a list of fixtures.
     *
     * @param Fixture[] $fixtures
     *
     * @return static
     */
    protected function addFixtures(iterable $fixtures)
    {
        foreach ($fixtures as $fixture) {
            $this->addFixture($fixture);
        }

        return $this;
    }

    /**
     * Adds a fixture.
     * Do nothing if the fixture was already added.
     *
     * @param Fixture $fixture
     *
     * @return static
     */
    protected function addFixture(Fixture $fixture)
    {
        $fixtureId = $this->getId($fixture);

        if (! $this->has($fixtureId)) {
            $this->fixtures[$fixtureId] = $fixture;
        }

        return $this;
    }

    /**
     * Gets the id of a fixture.
     * Internally the fully qualified name of the fixture is used.
     *
     * @param Fixture $fixture
     *
     * @return string
     */
    protected function getId(Fixture $fixture): string
    {
        return \get_class($fixture);
    }
}
