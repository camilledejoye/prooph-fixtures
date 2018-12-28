<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Fixture;

use ReflectionClass;

/**
 * Provides a getName() method which will return the short name of the fixture class.
 */
trait ShortNameFixture
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        $shortName = (new ReflectionClass($this))->getShortName();

        return \preg_replace('/Fixture$/', '', $shortName);
    }
}
