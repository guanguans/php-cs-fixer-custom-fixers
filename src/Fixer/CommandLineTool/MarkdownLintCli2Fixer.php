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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool;

use PhpCsFixer\FixerDefinition\CodeSample;

/**
 * @see https://github.com/DavidAnson/markdownlint-cli2
 */
final class MarkdownLintCli2Fixer extends AbstractCommandLineToolFixer
{
    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['markdownlint-cli2'];
    }

    /**
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return ['--fix', '--no-globs'];
    }

    /**
     * @return list<\PhpCsFixer\FixerDefinition\CodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new CodeSample(
                <<<'MD_WRAP'
                    # hello世界
                    MD_WRAP
            ), new CodeSample(
                <<<'MD_WRAP'
                    # hello世界

                    MD_WRAP
            ),
        ];
    }

    /**
     * @return non-empty-list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['md', 'markdown'];
    }
}
