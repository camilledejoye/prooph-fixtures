<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Tests\Fixture;

use PHPUnit\Framework\TestCase;
use Prooph\Fixtures\Fixture\ShortNameFixture;

class ShortNameFixtureTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_the_short_name_of_a_fixture()
    {
        $fixture = $this->getMockBuilder(ShortNameFixture::class)
            ->setMockClassName('SuperDuperFixture')
            ->getMockForTrait();

        $this->assertSame('SuperDuper', $fixture->getName());
    }
}
