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

namespace Guanguans\PhpCsFixerCustomFixers\Contract;

/**
 * @see https://github.com/deptrac/deptrac/blob/4.x/src/Contract/Dependency/DependencyInterface.php
 */
interface DependencyCommandContract
{
    public function dependencyCommand(): string;
}
