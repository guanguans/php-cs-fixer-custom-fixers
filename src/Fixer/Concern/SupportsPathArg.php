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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\Concern;

use Guanguans\PhpCsFixerCustomFixers\Support\Utils;
use Illuminate\Support\Str;

trait SupportsPathArg
{
    /**
     * ```shell
     * $ php vendor/bin/php-cs-fixer fix /path/to/file
     * ```.
     */
    public function supports(\SplFileInfo $file): bool
    {
        return Str::of($file)->contains(Utils::argv());
    }
}
