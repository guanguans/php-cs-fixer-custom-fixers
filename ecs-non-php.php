<?php

/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

use Guanguans\PhpCsFixerCustomFixers\Fixers;
use Guanguans\PhpCsFixerCustomFixers\Set\SetList;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    // ->withoutParallel()
    // ->withFileExtensions(
    //     collect(Fixers::make()->extensions())
    //         ->push('sh', 'bats', '*', 'txt', 'text', 'md', 'markdown')
    //         ->unique()
    //         ->all()
    // )
    ->withSkip([
        // 'README-zh_CN.md',
        'README.md',
        // 'composer.json',
    ])
    ->withSets([SetList::NON_PHP]);
