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
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\ConfigurableOfSkipPaths
 */
trait SupportsOfSkipPaths
{
    public function supports(\SplFileInfo $file): bool
    {
        return !Str::of($file)->is($this->configuration[self::SKIP_PATHS]);
    }
}
