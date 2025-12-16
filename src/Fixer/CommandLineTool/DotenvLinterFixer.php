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
 * @see https://github.com/dotenv-linter/dotenv-linter
 */
final class DotenvLinterFixer extends AbstractFixer
{
    /**
     * @return non-empty-list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['env', 'env.example'];
    }

    /**
     * @return list<\PhpCsFixer\FixerDefinition\CodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new CodeSample(
                <<<'ENV_WRAP'
                    FOO= BAR
                    BAR = FOO
                    ENV_WRAP
            ),
            new CodeSample(
                <<<'ENV_WRAP'
                    FOO=${BAR
                    BAR="$BAR}"
                    ENV_WRAP
            ),
            new CodeSample(
                <<<'ENV_WRAP'
                    FOO=BAR BAZ
                    ENV_WRAP
            ),
        ];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['dotenv-linter', 'fix'];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [];
    }
}
