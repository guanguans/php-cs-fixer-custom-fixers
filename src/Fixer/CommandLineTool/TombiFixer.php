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
 * @see https://github.com/tombi-toml/tombi
 * @see https://github.com/tox-dev/toml-fmt
 */
final class TombiFixer extends AbstractFixer
{
    /**
     * @return list<\PhpCsFixer\FixerDefinition\CodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new CodeSample(
                <<<'TOML_WRAP'
                    key1 = "value1"

                    key2 = "value2"
                    TOML_WRAP,
            ),
            new CodeSample(
                <<<'TOML_WRAP'
                    items = [
                      "aaaa",
                      "bbbb",
                      "cccc"
                    ]
                    TOML_WRAP,
            ),
            new CodeSample(
                <<<'TOML_WRAP'
                    items = ["aaaa", "bbbb", "cccc", "dddd", "eeee", "ffff", "gggg", "hhhh", "iiii","jjjj"]
                    TOML_WRAP,
            ),
        ];
    }

    /**
     * @return non-empty-list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['toml'];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['tombi', 'format'];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [
            '--no-cache' => true,
            '--offline' => true,
            '--verbose' => true,
        ];
    }
}
