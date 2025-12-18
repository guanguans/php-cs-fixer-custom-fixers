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

use Guanguans\PhpCsFixerCustomFixers\Fixer\InlineHtml\JsonFixer;
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
    public function testConfigure(): void
    {
        $this->fixer->configure($configuration = ['indent_string' => ' ']);

        self::assertSame(
            $configuration,
            array_intersect_key(
                // (fn (JsonFixer $fixer): array => $fixer->configuration)->call($this->fixer, $this->fixer),
                \Closure::bind(
                    static fn (JsonFixer $fixer): array => $fixer->configuration,
                    null,
                    JsonFixer::class
                )($this->fixer),
                $configuration
            )
        );
    }

    /**
     * @return iterable<int|string, array{0: string, 1?: null|string, 2?: array<string, mixed>}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'default' => [
            <<<'JSON_WRAP'
                {
                    "phrase": "你好！"
                }

                JSON_WRAP,
            <<<'JSON_WRAP'
                {
                    "phrase": "\u4f60\u597d\uff01"
                }
                JSON_WRAP,
        ];
    }
}
