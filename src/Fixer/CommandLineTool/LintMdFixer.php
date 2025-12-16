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
 * @see https://github.com/lint-md/lint-md
 * @see https://github.com/lint-md/cli
 */
final class LintMdFixer extends AbstractFixer
{
    /**
     * @return list<\PhpCsFixer\FixerDefinition\CodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new CodeSample(
                <<<'MD_WRAP'
                    ## 全角数字

                    > 这件蛋糕只卖 １０００ 元。
                    MD_WRAP
            ),
            new CodeSample(
                <<<'MD_WRAP'
                    ## 块引用空格

                    >   摇旗呐喊的热情
                    >携光阴渐远去
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

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['lint-md'];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [
            '--fix' => true,
        ];
    }
}
