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

use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concerns\PostFinalFileCommand;

/**
 * @see https://github.com/mvdan/sh
 */
final class ShfmtFixer extends AbstractCommandLineToolFixer
{
    use PostFinalFileCommand;

    /**
     * @see `-ln, --language-dialect str  bash/posix/mksh/bats, default "auto"`
     *
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['sh', 'bats'];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['shfmt'];
    }

    /**
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return [
            '--write',
            // '--simplify',
            // '--minify',
        ];
    }
}
