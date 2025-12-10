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
 * @see https://github.com/tombi-toml/tombi
 * @see https://github.com/tox-dev/toml-fmt
 */
final class TombiFixer extends AbstractCommandLineToolFixer
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            $summary = \sprintf('Format `%s` files using `tombi`.', $this->firstExtension()),
            [
                new CodeSample(
                    <<<'TOML_WRAP'
                        paths = [
                        "app/",
                        "bootstrap/",
                        "config/",
                        "tests/",
                        ]
                        TOML_WRAP,
                ), new CodeSample(
                    <<<'TOML_WRAP'
                        paths = [
                          "app/",
                          "bootstrap/",
                          "config/",
                          "tests/",
                        ]

                        TOML_WRAP,
                ),
            ],
            $summary,
            'Affected by `tombi`'
        );
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['tombi', 'format'];
    }

    /**
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return ['--offline', '--no-cache', '--verbose'];
    }

    /**
     * @return non-empty-list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['toml'];
    }
}
