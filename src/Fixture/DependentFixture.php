<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
