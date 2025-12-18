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
 * @see https://github.com/shufo/blade-formatter
 */
final class BladeFormatterFixer extends AbstractFixer
{
    /**
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['blade.php'];
    }

    /**
     * @noinspection HtmlUnknownTarget
     *
     * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new FileSpecificCodeSample(
                $blade = <<<'BLADE_WRAP'
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
                $this,
            ),
            new FileSpecificCodeSample($blade, $this, [self::OPTIONS => ['--indent-size' => 2, '--extra-liners' => true]]),
        ];
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
