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
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ZhlintFixer>
 */
final class ZhlintFixerTest extends AbstractFixerTestCase
{
    /**
     * @return iterable<int|string, array{0: string, 1?: null|string, 2?: array<string, mixed>}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'default' => [
            <<<'MD_WRAP'
                3 minute(s)left 中文

                case-abbr：Pure JavaScript(a.k.a. Vanilla)中文

                case-backslash：a \# b 中文\# __中文__ \# 中文 __\#__ __中文__\#中文 __\#__

                case-traditional：a “b ‘c’ d” e 中文

                mark-raw：a `b` c `d` e `f` g `h` i 中文

                mark-type：a__[b](x)__c__ [d](y) __e 中文

                space-brackets：(x)a(b)c(d)e(f)g(h)i(j)k(l)m__(a)__b(__c__)d(e)中文

                space-punctuation：中文。中文(中文)中文。中文。中文(中文)中文。

                space-quotations：a “hello world” b 中文

                unify-punctuation：中文，中文(中文)中文 ‘中文’ 中文 “中文” 中文(中文)(中文)中文(中文)。

                MD_WRAP,
            <<<'MD_WRAP'
                3 minute(s) left 中文

                case-abbr：Pure JavaScript (a.k.a. Vanilla) 中文

                case-backslash：a \# b 中文\# __中文__ \# 中文 __\#__ __中文__\#中文__\#__

                case-traditional：a「b『c』d」e 中文

                mark-raw：a `b` c `d`e`f` g`h`i 中文

                mark-type：a__[b](x)__c__[ d ](y)__e 中文

                space-brackets：(x)a(b)c (d )e( f) g ( h ) i（j）k （l） m __( a )__ b( __c__ )d(e) 中文

                space-punctuation：中文 。 中文(中文)中文。中文 . 中文（中文）中文.

                space-quotations: a " hello world " b 中文

                unify-punctuation：中文,中文 （中文） 中文'中文'中文"中文"中文 （中文）（中文）中文 （中文）。
                MD_WRAP,
        ];
    }
}
