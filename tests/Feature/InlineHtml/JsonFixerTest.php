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

namespace Guanguans\PhpCsFixerCustomFixersTests\Feature\InlineHtml;

use Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractFixerTestCase;

/**
 * @internal
 *
 * @covers \Guanguans\PhpCsFixerCustomFixers\Fixer\InlineHtml\JsonFixer
 *
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\InlineHtml\JsonFixer>
 */
final class JsonFixerTest extends AbstractFixerTestCase
{
    /**
     * @dataProvider provideFixCases
     */
    public function testFix(string $expected, ?string $input = null): void
    {
        $this->doTest($expected, $input, $this->fixer->makeDummySplFileInfo());
    }

    /**
     * @return iterable<string, array{0: string, 1?: string}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'JSON_UNESCAPED_UNICODE' => [
            <<<'JSON'
                {
                    "phrase": "你好！"
                }

                JSON,
            <<<'JSON'
                {
                    "phrase": "\u4f60\u597d\uff01"
                }
                JSON,
        ];
    }
}
