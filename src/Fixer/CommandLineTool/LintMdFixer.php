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
 * @see https://github.com/lint-md/lint-md
 * @see https://github.com/lint-md/cli
 */
final class LintMdFixer extends AbstractCommandLineToolFixer
{
    public function installationCommand(): string
    {
        switch (\PHP_OS_FAMILY) {
            case 'Darwin':
            case 'Windows':
            case 'Linux':
            default:
                return 'npm install -g @lint-md/cli';
        }
    }

    /**
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['md', 'markdown'];
    }

    /**
     * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new FileSpecificCodeSample(
                <<<'MD_WRAP'
                    ## 全角数字

                    > 这件蛋糕只卖 １０００ 元。

                    MD_WRAP,
                $this,
            ),
            new FileSpecificCodeSample(
                <<<'MD_WRAP'
                    ## 块引用空格

                    >   摇旗呐喊的热情

                    >携光阴渐远去

                    MD_WRAP,
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
        return ['lint-md'];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [
            '--fix' => true,
        ];
    }
}
