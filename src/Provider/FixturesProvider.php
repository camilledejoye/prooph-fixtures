<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Provider;

use Prooph\Fixtures\Fixture\Fixture;
use Psr\Container\ContainerInterface;

interface FixturesProvider extends ContainerInterface
{
    /**
     * {@inheritdoc}
     *
     * @return Fixture|null
     */
    public function get($id);

    /**
     * {@inheritdoc}
     */
    public function has($id);

    /**
     * Gets all the loaded fixtures.
     * With they fully qualified name as key.
     *
     * @return Fixture[]
     */
    public function all(): array;
}
