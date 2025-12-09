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
 * @see https://github.com/huacnlee/autocorrect
 */
final class AutocorrectFixer extends AbstractCommandLineToolFixer
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            $summary = \sprintf('Format `%s` files using `autocorrect`.', $this->defaultExtensions()[0]),
            [
                new CodeSample(
                    <<<'TEXT_WRAP'
                        hello世界！
                        TEXT_WRAP
                ), new CodeSample(
                    <<<'TEXT_WRAP'
                        hello 世界！
                        TEXT_WRAP
                ),
            ],
            $summary,
            'Affected by `autocorrect`'
        );
    }

    /**
     * @return non-empty-list<string>
     */
    public function defaultExtensions(): array
    {
        return ['md', 'markdown', 'txt', 'text'];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['autocorrect'];
    }

    /**
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return ['--fix'];
    }
}
