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

/**
 * @see https://github.com/lint-md
 * @see https://github.com/lint-md/cli
 */
final class LintMdFixer extends AbstractCommandLineToolFixer
{
    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['lint-md'];
    }

    /**
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return ['--fix'];
    }

    /**
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['md', 'markdown'];
    }
}
