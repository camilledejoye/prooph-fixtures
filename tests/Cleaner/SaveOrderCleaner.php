<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Tests\Cleaner;

use Prooph\Fixtures\Cleaner\Cleaner;

class SaveOrderCleaner implements Cleaner
{
    public $order = -1;
    private static $innerOrder = 0;

    public function __invoke(): void
    {
        $this->order = self::$innerOrder++;
    }
}
