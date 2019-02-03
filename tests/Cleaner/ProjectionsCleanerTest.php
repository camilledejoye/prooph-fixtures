<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Tests\Cleaner;

use PHPUnit\Framework\TestCase;
use Prooph\Fixtures\Cleaner\CleaningProjectionStrategy;
use Prooph\Fixtures\Cleaner\ProjectionsCleaner;

class ProjectionsCleanerTest extends TestCase
{
    /**
     * @test
     */
    public function it_cleans_all_the_projection()
    {
        $projectionNames = ['a projection', 'another projection'];
        $cleaningStrategy = $this->getMockForAbstractClass(CleaningProjectionStrategy::class);

        $cleaningStrategy->expects($this->exactly(\count($projectionNames)))
            ->method('clean')
            ->withConsecutive(...\array_map(function ($projectionName) {
                return [$projectionName];
            }, $projectionNames));

        $projectionsCleaner = new ProjectionsCleaner($cleaningStrategy, $projectionNames);
        $projectionsCleaner();
    }
}
