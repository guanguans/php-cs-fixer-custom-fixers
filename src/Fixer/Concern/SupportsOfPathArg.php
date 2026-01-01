<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\Concern;

use Illuminate\Support\Str;

trait SupportsOfPathArg
{
    /**
     * @see \PhpCsFixer\Console\Application
     * @see \PhpCsFixer\Console\Command\CheckCommand
     * @see \PhpCsFixer\Console\Command\FixCommand
     *
     * ```shell
     * $ php vendor/bin/php-cs-fixer fix /path/to/file
     * ```.
     */
    public function supports(\SplFileInfo $file): bool
    {
        return Str::of($file)->contains(array_filter(
            $_SERVER['argv'],
            static fn (string $arg): bool => !str_starts_with($arg, '--') && (is_file($arg) || is_dir($arg))
        ));
    }
}
