<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Cleaner;

use Prooph\EventStore\Exception\ProjectionNotFound;
use Prooph\Fixtures\Cleaner\Exception\CleaningProjectionFailed;

interface CleaningProjectionStrategy
{
    /**
     * Cleans up a projection.
     *
     * @param string $projectionName
     *
     * @return void
     *
     * @throws ProjectionNotFound No projection was found for the given name.
     * @throws CleaningProjectionFailed An error occured during the cleaning.
     */
    public function clean(string $projectionName): void;
}
