<?php

/**
 * This file is part of elythy/prooph-fixtures.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Cleaner;

/**
 * Clean up something related to the fixtures.
 */
interface Cleaner
{
    /**
     * Cleans up.
     *
     * @return void
     */
    public function __invoke(): void;
}
