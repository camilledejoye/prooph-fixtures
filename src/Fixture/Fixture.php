<?php

/**
 * This file is part of elythy/prooph-fixtures.
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
