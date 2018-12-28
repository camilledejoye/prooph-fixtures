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
use Prooph\Fixtures\Cleaner\ChainCleaner;
use Prooph\Fixtures\Cleaner\Cleaner;
use TypeError;

final class ChainCleanerTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_an_error_if_a_cleaner_does_not_implement_the_interface()
    {
        $this->expectException(TypeError::class);

        new ChainCleaner([
            $this->createCleaner(),
            new \StdClass(),
        ]);
    }

    /**
     * @test
     */
    public function it_calls_all_cleaners_in_the_right_order()
    {
        $firstCleaner = $this->createCleaner();
        $secondCleaner = $this->createCleaner();

        $sut = (new ChainCleaner())
            ->add($firstCleaner)
            ->add($secondCleaner);
        $sut();

        $this->assertEquals(0, $firstCleaner->order);
        $this->assertEquals(1, $secondCleaner->order);
    }

    private function createCleaner(): Cleaner
    {
        return new SaveOrderCleaner();
    }
}
