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
 * @see https://github.com/google/yamlfmt
 */
final class YamlfmtFixer extends AbstractCommandLineToolFixer
{
    /**
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['yaml', 'yml'];
    }

    /**
     * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new FileSpecificCodeSample(
                <<<'YAML_WRAP'
                    issues:
                        types: [ opened ]

                    YAML_WRAP,
                $this,
            ),
            new FileSpecificCodeSample(
                <<<'YAML_WRAP'
                    to_be_merged: &tbm
                      key1: value1
                    merged_map:
                      <<: *tbm

                    YAML_WRAP,
                $this,
                [],
            ),
            new FileSpecificCodeSample(
                <<<'YAML_WRAP'
                    commands: >
                      [ -f "/usr/local/bin/foo" ] &&
                      echo "skip install" ||
                      go install github.com/foo/foo@latest

                    YAML_WRAP,
                $this,
                [],
            ),
        ];
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
