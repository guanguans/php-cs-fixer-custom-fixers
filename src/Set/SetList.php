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

namespace Guanguans\PhpCsFixerCustomFixers\Set;

/**
 * @api
 */
final class SetList
{
    /** @api */
    public const GUANGUANS = __DIR__.'/../../config/set/guanguans.php';

    /** @api */
    public const DYNAMIC = __DIR__.'/../../config/set/dynamic.php';

    /** @api */
    public const CUSTOM = __DIR__.'/../../config/set/custom.php';

    /** @api */
    public const COMMAND_LINE_TOOL = __DIR__.'/../../config/set/command-line-tool.php';
}
