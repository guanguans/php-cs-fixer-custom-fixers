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
 * @see https://github.com/textlint/textlint
 */
final class TextLintFixer extends AbstractCommandLineToolFixer
{
    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['textlint'];
    }

    /**
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return ['--fix', '--experimental'];
    }

    /**
     * @return list<\PhpCsFixer\FixerDefinition\CodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new CodeSample(
                <<<'TEXT_WRAP'
                    jquery is javascript library.
                    TEXT_WRAP,
                [
                    self::OPTIONS => ['--rule' => 'terminology'],
                ]
            ), new CodeSample(
                <<<'TEXT_WRAP'
                    jQuery is JavaScript library.
                    TEXT_WRAP,
                [
                    self::OPTIONS => ['--rule' => 'terminology'],
                ]
            ),
        ];
    }

    /**
     * @return non-empty-list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['md', 'markdown', 'txt', 'text'];
    }
}
