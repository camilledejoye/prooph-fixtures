<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Tests\Fixtures;

use Prooph\Fixtures\Fixture\Fixture;
use Prooph\Fixtures\Fixture\ShortNameFixture;

class UsersFixture implements Fixture
{
    use ShortNameFixture;

    /**
     * {@inheritdoc}
     */
    public function load(): void
    {
    }
}
