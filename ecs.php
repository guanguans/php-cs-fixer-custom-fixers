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

use Guanguans\PhpCsFixerCustomFixers\Set\SetList;
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use PhpCsFixer\Fixer\FunctionNotation\StaticLambdaFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    // ->withoutParallel()
    ->withPaths(Utils::defaultPaths())
    ->withSkip([
        // StaticLambdaFixer::class => [
        //     __DIR__.'/src/ReleaseWorker/RunComposerScriptsReleaseWorker.php',
        //     __DIR__.'/tests/*Test.php',
        //     __DIR__.'/tests/Pest.php',
        // ],
    ])
    ->withSets([SetList::GUANGUANS])
    ->withConfiguredRule(HeaderCommentFixer::class, Utils::configurationOfHeaderCommentFixer(
        'guanguans/php-cs-fixer-custom-fixers',
        '2025',
        __DIR__.'/LICENSE'
    ));
