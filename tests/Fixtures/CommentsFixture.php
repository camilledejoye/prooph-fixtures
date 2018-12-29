<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Tests\Fixtures;

use Prooph\Fixtures\Fixture\DependentFixture;
use Prooph\Fixtures\Fixture\ShortNameFixture;

class CommentsFixture implements DependentFixture
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
            UsersFixture::class,
            ArticlesFixture::class,
        ];
    }
}
