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

use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;

/**
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\Configurable
 *
 * @property array{
 *     skip_paths: list<string>,
 * } $configuration
 */
trait ConfigurableOfSkipPaths
{
    // /** @var string */
    // public const SKIP_PATHS = 'skip_paths';

    /**
     * @see \PhpCsFixer\Fixer\Whitespace\SkipPathsFixer
     *
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function fixerOptionOfSkipPaths(): FixerOptionInterface
    {
        return (new FixerOptionBuilder(self::SKIP_PATHS, 'List of paths to skip.'))
            ->setAllowedTypes(['string[]'])
            ->setDefault([])
            ->getOption();
    }
}
