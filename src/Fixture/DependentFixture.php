<?php

/**
 * This file is part of elythy/prooph-fixtures.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Fixture;

abstract class DependentFixture implements Fixture
{
    /**
     * Gets the dependencies of a fixture.
     * Express a dependency to another fixture with its fully qualified name.
     *
     * @return string[]
     */
    abstract public static function getDependencies(): array;

    /**
     * Checks if a fixture depends on another.
     *
     * @param Fixture $other
     *
     * @return bool
     */
    public static function dependsOn(Fixture $other): bool
    {
        return \in_array(\get_class($other), static::getDependencies());
    }
}
