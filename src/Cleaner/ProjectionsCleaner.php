<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Cleaner;

use Prooph\EventStore\Exception\RuntimeException;
use Psr\Container\ContainerInterface;

/**
 * Resets all the projections.
 *
 * @see Cleaner
 */
final class ProjectionsCleaner implements Cleaner
{
    /**
     * @var ContainerInterface
     */
    private $projectionManagersLocator;

    /**
     * @var array
     */
    private $projectionManagerNames;

    /**
     * @var int Number of projections handled at once.
     */
    private $batchSize;

    /**
     * Creates a new projections cleaner.
     *
     * @param ContainerInterface $projectionManagersLocator
     * @param array $projectionManagerNames
     * @param int $batchSize Number of projections handled at once.
     */
    public function __construct(
        ContainerInterface $projectionManagersLocator,
        array $projectionManagerNames,
        int $batchSize = 10000
    ) {
        $this->projectionManagersLocator = $projectionManagersLocator;
        $this->projectionManagerNames = $projectionManagerNames;
        $this->batchSize = $batchSize;
    }

    /**
     * Cleans all the currently registered projections by resetting them.
     * A projection is only register after having being runned manually.
     *
     * @return void
     */
    public function __invoke(): void
    {
        foreach ($this->getAllProjectionManagers() as $projectionManager) {
            $offset = 0;
            while ($projectionNames = $projectionManager->fetchProjectionNames(null, $this->batchSize, $offset)) {
                foreach ($projectionNames as $projectionName) {
                    $projectionManager->resetProjection($projectionName);
                }

                $offset += $this->batchSize;
            }
        }
    }

    /**
     * Gets all the projection managers.
     *
     * @return ProjectionManager[]
     */
    private function getAllProjectionManagers(): iterable
    {
        $projectionManagerNames = \array_keys($this->projectionManagerNames);

        foreach ($projectionManagerNames as $projectionManagerName) {
            if (! $this->projectionManagersLocator->has($projectionManagerName)) {
                throw new RuntimeException(\sprintf(
                    'Projection manager "%s" not found.',
                    $projectionManagerName
                ));
            }

            yield $this->projectionManagersLocator->get($projectionManagerName);
        }
    }
}
