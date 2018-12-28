<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
