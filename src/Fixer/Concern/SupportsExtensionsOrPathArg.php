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

trait SupportsExtensionsOrPathArg
{
    use SupportsExtensions {
        SupportsExtensions::supports as supportsExtensions;
    }
    use SupportsPathArg{
        SupportsPathArg::supports as supportsPathArg;
    }

    public function supports(\SplFileInfo $file): bool
    {
        return $this->supportsExtensions($file) || $this->supportsPathArg($file);
    }
}
