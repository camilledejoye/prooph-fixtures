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

/**
 * Resets all the projections.
 *
 * @see Cleaner
 */
final class ProjectionsCleaner implements Cleaner
{
    /**
     * @var CleaningProjectionStrategy
     */
    private $cleaningStrategy;
    /**
     * @var iterable
     */
    private $projectionsNames;

    /**
     * Creates a new projections cleaner.
     *
     * @param CleaningProjectionStrategy $cleaningStrategy
     * @param string[] $projectionsNames The names of the projections to clean.
     */
    public function __construct(
        CleaningProjectionStrategy $cleaningStrategy,
        iterable $projectionsNames
    ) {
        $this->cleaningStrategy = $cleaningStrategy;
        $this->projectionsNames = $projectionsNames;
    }

    /**
     * Cleans all the projections.
     *
     * @return void
     *
     * @throws ProjectionNotFound No projection was found for the given name.
     * @throws CleaningProjectionFailed An error occured during the cleaning.
     */
    public function __invoke(): void
    {
        foreach ($this->projectionsNames as $projectionName) {
            $this->cleaningStrategy->clean($projectionName);
        }
    }
}
