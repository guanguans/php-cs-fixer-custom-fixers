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
 * @see https://github.com/google/yamlfmt
 */
final class YamlFmtFixer extends AbstractCommandLineToolFixer
{
    /**
     * @return list<string>
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
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return ['-gitignore_excludes'];
    }
}
