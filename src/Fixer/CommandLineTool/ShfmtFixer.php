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

use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyCommandContract;
use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyNameContract;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern\PostFinalFileCommand;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\DependencyName;
use Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample;

/**
 * @see https://github.com/mvdan/sh
 */
final class ShfmtFixer extends AbstractCommandLineToolFixer implements DependencyCommandContract, DependencyNameContract
{
    use DependencyName;
    use PostFinalFileCommand;

    public function dependencyCommand(): string
    {
        switch (\PHP_OS_FAMILY) {
            case 'Darwin':
                return 'brew install shfmt';
            case 'Windows':
            case 'Linux':
            default:
                return 'go install mvdan.cc/sh/v3/cmd/shfmt@latest';
        }
    }

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
     * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new FileSpecificCodeSample(
                $sh = <<<'SH_WRAP'
                    #!/bin/bash

                    if foo ;   then
                        bar
                    fi

                    for i in 1 2 3; do
                                bar
                    done

                    SH_WRAP,
                $this,
            ),
            new FileSpecificCodeSample($sh, $this, [self::OPTIONS => ['--minify' => true]]),
        ];
    }

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
}
