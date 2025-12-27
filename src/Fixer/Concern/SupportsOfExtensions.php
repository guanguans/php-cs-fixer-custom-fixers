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

/**
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\ConfigurableOfExtensions
 */
trait SupportsOfExtensions
{
    public function supports(\SplFileInfo $file): bool
    {
        $lowerExtensions = array_map(static fn (string $ext): string => strtolower($ext), $this->extensions());

        return Str::of($file->getExtension())->lower()->is($lowerExtensions)
            || Str::of($file->getBasename())->lower()->endsWith($lowerExtensions);
    }
}
