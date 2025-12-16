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
 * @see https://github.com/google/yamlfmt
 */
final class YamlfmtFixer extends AbstractFixer
{
    /**
     * @return list<\PhpCsFixer\FixerDefinition\CodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new CodeSample(
                <<<'YAML_WRAP'
                    issues:
                        types: [ opened ]
                    YAML_WRAP
            ),
            new CodeSample(
                <<<'YAML_WRAP'
                    to_be_merged: &tbm
                      key1: value1
                    merged_map:
                      <<: *tbm
                    YAML_WRAP
            ),
            new CodeSample(
                <<<'YAML_WRAP'
                    commands: >
                      [ -f "/usr/local/bin/foo" ] &&
                      echo "skip install" ||
                      go install github.com/foo/foo@latest
                    YAML_WRAP
            ),
        ];
    }

    /**
     * @return non-empty-list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['yaml', 'yml'];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['yamlfmt'];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [
            '-gitignore_excludes' => true,
        ];
    }
}
