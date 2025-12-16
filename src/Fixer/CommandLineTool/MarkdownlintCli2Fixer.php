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
final class MarkdownlintCli2Fixer extends AbstractFixer
{
    /**
     * @return list<\PhpCsFixer\FixerDefinition\CodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new CodeSample(
                <<<'MD_WRAP'
                    # Examples
                    ## This is ordered list

                    1.    First item
                    2. Second item

                    ## This is unordered list

                    * https://link.com
                    * [ this is link  ](https://link.com   )
                    * ** bold text **
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
        return ['markdownlint-cli2'];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [
            '--fix' => true,
            '--no-globs' => true,
        ];
    }
}
