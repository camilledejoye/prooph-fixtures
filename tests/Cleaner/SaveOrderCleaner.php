<?php

/**
 * This file is part of elythy/prooph-fixtures.
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
