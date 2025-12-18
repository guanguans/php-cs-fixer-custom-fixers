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

namespace Guanguans\PhpCsFixerCustomFixersTests\Feature\CommandLineTool;

use Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractFixerTestCase;

/**
 * @internal
 *
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\BladeFormatterFixer>
 */
final class BladeFormatterFixerTest extends AbstractFixerTestCase
{
    /**
     * @noinspection HtmlUnknownTarget
     *
     * @return iterable<int|string, array{0: string, 1?: null|string, 2?: array<string, mixed>}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'default' => [
            <<<'BLADE_WRAP'
                @if ($paginator->hasPages())
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
        ];
    }
}
