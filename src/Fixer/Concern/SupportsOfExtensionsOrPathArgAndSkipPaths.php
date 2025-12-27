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

trait SupportsOfExtensionsOrPathArgAndSkipPaths
{
    use SupportsOfExtensions {
        SupportsOfExtensions::supports as supportsExtensions;
    }
    use SupportsOfPathArg{
        SupportsOfPathArg::supports as supportsPathArg;
    }
    use SupportsOfSkipPaths{
        SupportsOfSkipPaths::supports as supportsSkipPaths;
    }

    public function supports(\SplFileInfo $file): bool
    {
        return $this->supportsSkipPaths($file) && (
            $this->supportsExtensions($file) || $this->supportsPathArg($file)
        );
    }
}
