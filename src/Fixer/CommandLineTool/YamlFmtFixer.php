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
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;

/**
 * @see https://github.com/google/yamlfmt
 */
final class YamlFmtFixer extends AbstractCommandLineToolFixer
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            $summary = \sprintf('Format `%s` files using `yamlfmt`.', $this->defaultExtensions()[0]),
            [
                new CodeSample(
                    <<<'YAML_WRAP'
                        on:
                            issues:
                                types: [ opened ]
                        YAML_WRAP
                ), new CodeSample(
                    <<<'YAML_WRAP'
                        on:
                          issues:
                            types: [opened]

                        YAML_WRAP
                ),
            ],
            $summary,
            'Affected by `yamlfmt`'
        );
    }

    /**
     * @return non-empty-list<string>
     */
    public function defaultExtensions(): array
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
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return ['-gitignore_excludes'];
    }
}
