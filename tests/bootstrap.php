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

use DG\BypassFinals;

require __DIR__.'/../vendor/autoload.php';

BypassFinals::enable(false);
BypassFinals::allowPaths([
    '*/src/Fixer/CommandLineTool/GenericsFixer.php',
]);
