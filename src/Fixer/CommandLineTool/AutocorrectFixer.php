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
 * @see https://github.com/huacnlee/autocorrect
 */
final class AutocorrectFixer extends AbstractCommandLineToolFixer
{
    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['autocorrect'];
    }

    /**
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return ['--fix'];
    }

    /**
     * @return list<\PhpCsFixer\FixerDefinition\CodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new CodeSample(
                <<<'TEXT_WRAP'
                    hello世界！
                    TEXT_WRAP
            ), new CodeSample(
                <<<'TEXT_WRAP'
                    hello 世界！
                    TEXT_WRAP
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
