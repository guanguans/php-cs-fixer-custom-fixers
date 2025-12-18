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

use Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample;

/**
 * @see https://github.com/textlint/textlint
 * @see https://github.com/textlint/textlint/wiki/Collection-of-textlint-rule
 * @see https://github.com/sapegin/textlint-rule-terminology
 */
final class TextlintFixer extends AbstractFixer
{
    /**
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['txt', 'text', 'md', 'markdown'];
    }

    /**
     * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new FileSpecificCodeSample(
                <<<'TEXT_WRAP'
                    jquery is javascript library.
                    TEXT_WRAP,
                $this,
                [
                    self::OPTIONS => ['--rule' => 'terminology'],
                ],
            ),
        ];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['textlint'];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [
            '--experimental' => true,
            '--fix' => true,
        ];
    }
}
