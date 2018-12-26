<?php

/**
 * This file is part of elythy/prooph-fixtures.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Tests\Cleaner;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\StreamName;
use Prooph\Fixtures\Cleaner\EventStreamsCleaner;

class EventStreamsCleanerTest extends TestCase
{
    /**
     * @test
     */
    public function it_deletes_all_event_streams()
    {
        $eventStore = $this->createMock(EventStore::class);
        $batchSize = 2;
        $sut = new EventStreamsCleaner($eventStore, $batchSize);

        $streams = [
            $this->createMock(StreamName::class),
            $this->createMock(StreamName::class),
            $this->createMock(StreamName::class),
        ];

        $this->assertThatAllEventsAreRetrieved($eventStore, $streams, $batchSize);
        $this->assertThatStreamsAreDeleted($eventStore, $streams);

        $sut();
    }

    private function assertThatAllEventsAreRetrieved(MockObject $eventStore, array $streams, int $batchSize): void
    {
        $eventStore->expects($this->exactly(\count($streams)))
            ->method('fetchStreamNames')
            ->withConsecutive(
                [$this->equalTo(null), $this->equalTo(null), $this->equalTo($batchSize), $this->equalTo(0)],
                [$this->equalTo(null), $this->equalTo(null), $this->equalTo($batchSize), $this->equalTo($batchSize)],
                [$this->equalTo(null), $this->equalTo(null), $this->equalTo($batchSize), $this->equalTo($batchSize * 2)]
            )
            ->willReturnOnConsecutiveCalls(
                \array_slice($streams, 0, $batchSize),
                \array_slice($streams, $batchSize, $batchSize),
                []
            );
    }

    /**
     * @param MockObject[] $streams
     */
    private function assertThatStreamsAreDeleted(MockObject $eventStore, array $streams): void
    {
        $eventStore->expects($this->exactly(\count($streams)))
            ->method('delete')
            ->withConsecutive(
                [$this->identicalTo($streams[0])],
                [$this->identicalTo($streams[1])],
                [$this->identicalTo($streams[2])]
            );
    }
}
