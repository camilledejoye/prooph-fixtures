<?php

/**
 * This file is part of elythy/prooph-fixtures.
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
