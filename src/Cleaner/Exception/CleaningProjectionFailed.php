<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Cleaner\Exception;

use Prooph\EventStore\Exception\RuntimeException;

class CleaningProjectionFailed extends RuntimeException
{
}
