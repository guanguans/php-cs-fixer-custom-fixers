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
use PhpCsFixer\FixerDefinition\CodeSample;

/**
 * @see https://github.com/mvdan/sh
 */
final class ShfmtFixer extends AbstractCommandLineToolFixer
{
    use PostFinalFileCommand;

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['shfmt'];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [
            // '--minify' => true,
            // '--simplify' => true,
            '--write' => true,
        ];
    }

    /**
     * @return list<\PhpCsFixer\FixerDefinition\CodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new CodeSample(
                <<<'SH_WRAP'
                    #!/bin/bash

                    if foo ;   then
                        bar
                    fi

                    for i in 1 2 3; do
                                bar
                    done
                    SH_WRAP
            ),
            new CodeSample(
                <<<'SH_WRAP'
                    #!/bin/bash

                    if foo ;   then
                        bar
                    fi

                    for i in 1 2 3; do
                                bar
                    done
                    SH_WRAP,
                [self::OPTIONS => ['--minify' => true]]
            ),
        ];
    }

    /**
     * @see `-ln, --language-dialect str  bash/posix/mksh/bats, default "auto"`
     *
     * @return non-empty-list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['sh', 'bats'];
    }
}
