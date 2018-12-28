<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Cleaner;

use Prooph\EventStore\EventStore;

/**
 * Cleans all event streams.
 *
 * @see Cleaner
 */
final class EventStreamsCleaner implements Cleaner
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @var int Number of streams handled at once.
     */
    private $batchSize;

    /**
     * Creates a new event streams cleaner.
     *
     * @param EventStore $eventStore
     * @param int $batchSize Number of streams handled at once.
     */
    public function __construct(EventStore $eventStore, int $batchSize = 10000)
    {
        $this->eventStore = $eventStore;
        $this->batchSize = $batchSize;
    }

    /**
     * Cleans all the existing event streams.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $offset = 0;
        while ($streamNames = $this->eventStore->fetchStreamNames(null, null, $this->batchSize, $offset)) {
            foreach ($streamNames as $streamName) {
                $this->eventStore->delete($streamName);
            }

            $offset += $this->batchSize;
        }
    }
}
