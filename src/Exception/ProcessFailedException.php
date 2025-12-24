<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

namespace Guanguans\PhpCsFixerCustomFixers\Exception;

use Guanguans\PhpCsFixerCustomFixers\Contract\ThrowableContract;
use Symfony\Component\Process\Exception\ProcessFailedException as ProcessProcessFailedException;

final class ProcessFailedException extends ProcessProcessFailedException implements ThrowableContract {}
