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

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

return (new Configuration)
    ->addPathsToScan(
        [
            // __DIR__.'/src/',
        ],
        false
    )
    ->addPathsToExclude([
        __DIR__.'/src/Support/ComposerScripts.php',
        __DIR__.'/tests/',
    ])
    ->ignoreUnknownClasses([
        // \SensitiveParameter::class,
    ])
    /** @see \ShipMonk\ComposerDependencyAnalyser\Analyser::CORE_EXTENSIONS */
    ->ignoreErrorsOnExtensions(
        [
            // 'ext-ctype',
            // 'ext-mbstring',
        ],
        [ErrorType::SHADOW_DEPENDENCY],
    )
    ->ignoreErrorsOnPackageAndPath(
        'illuminate/collections',
        __DIR__.'/src/Support/helpers.php',
        [ErrorType::SHADOW_DEPENDENCY]
    )
    ->ignoreErrorsOnPackages(
        [
            'symfony/process',
        ],
        [ErrorType::UNUSED_DEPENDENCY]
    );
