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
 * @see https://github.com/shufo/blade-formatter
 */
final class BladeFormatterFixer extends AbstractCommandLineToolFixer
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            $summary = \sprintf('Format `%s` files using `blade-formatter`.', $this->defaultExtensions()[0]),
            [
                new CodeSample(
                    <<<'BLADE_WRAP'
                        <!DOCTYPE html>
                        <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
                        <body
                        class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
                        @if (Route::has('login'))
                        <div class="h-14.5 hidden lg:block"></div>
                        @endif
                        </body>
                        </html>
                        BLADE_WRAP
                ), new CodeSample(
                    <<<'WRAP'
                        <!DOCTYPE html>
                        <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

                        <body
                            class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
                            @if (Route::has('login'))
                                <div class="h-14.5 hidden lg:block"></div>
                            @endif
                        </body>

                        </html>

                        WRAP
                ),
            ],
            $summary,
            'Affected by `blade-formatter`'
        );
    }

    /**
     * @return non-empty-list<string>
     */
    public function defaultExtensions(): array
    {
        return ['blade.php'];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['blade-formatter'];
    }

    /**
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return ['--write'];
    }
}
