<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Fixture;

interface DependentFixture extends Fixture
{
    /**
     * Gets the dependencies of a fixture.
     * Express a dependency to another fixture with its fully qualified name.
     *
     * @return string[]
     */
    public static function getDependencies(): array;
}
