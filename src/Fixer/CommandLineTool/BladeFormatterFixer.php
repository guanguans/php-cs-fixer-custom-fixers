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

/**
 * @see https://github.com/shufo/blade-formatter
 */
final class BladeFormatterFixer extends AbstractFixer
{
    /**
     * @noinspection HtmlUnknownTarget
     *
     * @return list<\PhpCsFixer\FixerDefinition\CodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new CodeSample(
                <<<'BLADE_WRAP'
                    @if($paginator->hasPages())
                        <nav>
                            <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())

                                   <li class="disabled" aria-disabled="true"><span>@lang('pagination.previous')</span></li>
                            @else

                                   <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a></li>
                            @endif
                            </ul>
                        </nav>
                    @endif
                    BLADE_WRAP,
            ),
            new CodeSample(
                <<<'BLADE_WRAP'
                    @if($paginator->hasPages())
                        <nav>
                            <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())

                                   <li class="disabled" aria-disabled="true"><span>@lang('pagination.previous')</span></li>
                            @else

                                   <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a></li>
                            @endif
                            </ul>
                        </nav>
                    @endif
                    BLADE_WRAP,
                [self::OPTIONS => ['--indent-size' => 2, '--extra-liners' => true]],
            ),
        ];
    }

    /**
     * @return non-empty-list<string>
     */
    protected function defaultExtensions(): array
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
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [
            '--write' => true,
        ];
    }
}
