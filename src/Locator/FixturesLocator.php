<?php

/**
 * This file is part of elythy/prooph-fixtures.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Locator;

use Prooph\Fixtures\Fixture\Fixture;
use Psr\Container\ContainerInterface;

interface FixturesLocator extends ContainerInterface
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
    public function getFixtures(): array;
}
