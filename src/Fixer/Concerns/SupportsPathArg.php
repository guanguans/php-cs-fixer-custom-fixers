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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\Concerns;

use Guanguans\PhpCsFixerCustomFixers\Support\Utils;
use Illuminate\Support\Str;

trait SupportsPathArg
{
    public function supports(\SplFileInfo $file): bool
    {
        // This is a workaround for the `--path` argument in the command line.
        // It checks if the file path contains the `--path` argument.
        return Str::of($file)->contains(Utils::argv());
    }
}
