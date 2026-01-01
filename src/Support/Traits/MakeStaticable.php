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

namespace Guanguans\PhpCsFixerCustomFixers\Support\Traits;

trait MakeStaticable
{
    /**
     * @noinspection PhpMethodParametersCountMismatchInspection
     *
     * @param mixed ...$parameters
     */
    public static function make(...$parameters): self
    {
        return new static(...$parameters);
    }
}
