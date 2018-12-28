<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Fixture;

/**
 * Common interfaces for all Prooph fixtures.
 */
interface Fixture
{
    /**
     * Loads a fixture.
     *
     * @return void
     */
    public function load(): void;

    /**
     * Gets the name of a fixture.
     *
     * @return string
     */
    public function getName(): string;
}
