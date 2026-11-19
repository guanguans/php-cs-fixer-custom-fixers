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
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    // ->withoutParallel()
    // ->withFileExtensions(
    //     collect(Fixers::make()->extensions())
    //         ->push('Dockerfile', 'env', 'env.example', 'sh', 'bats')
    //         ->unique()
    //         ->all()
    // )
    // ->withPaths([
    //     ...Utils::defaultNonPhpPaths(),
    //     __DIR__.'/tests/Fixtures/',
    // ])
    // ->withSkip([])
    ->withSets([SetList::NON_PHP]);
