<?php

/**
 * This file is part of elythy/prooph-fixtures.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Tests\Fixtures;

use Prooph\Fixtures\Fixture\DependentFixture;
use Prooph\Fixtures\Fixture\ShortNameFixture;

class ArticlesCircularReferenceFixture extends DependentFixture
{
    use ShortNameFixture;

    /**
     * {@inheritdoc}
     */
    public function load(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [
            UsersCircularReferenceFixture::class,
        ];
    }
}
