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
 * @see https://github.com/DavidAnson/markdownlint-cli2
 */
final class MarkdownLintCli2Fixer extends AbstractCommandLineToolFixer
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            $summary = \sprintf('Format `%s` files using `markdownlint-cli2`.', $this->defaultExtensions()[0]),
            [
                new CodeSample(
                    <<<'MD_WRAP'
                        # hello世界
                        MD_WRAP
                ), new CodeSample(
                    <<<'MD_WRAP'
                        # hello世界

                        MD_WRAP
                ),
            ],
            $summary,
            'Affected by `markdownlint-cli2`'
        );
    }

    /**
     * @return non-empty-list<string>
     */
    public function defaultExtensions(): array
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
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return ['--fix', '--no-globs'];
    }
}
