<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 guanguans<ityaozm@gmail.com>
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
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\TombiFixer>
 */
final class TombiFixerTest extends AbstractFixerTestCase
{
    /**
     * @return iterable<int|string, array{0: string, 1?: null|string, 2?: array<string, mixed>}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'default' => [
            <<<'TOML_WRAP'
                items = [
                  "aaa",
                  "bbb",
                  "ccc",
                  "ddd",
                  "eee",
                  "fff",
                  "ggg",
                  "hhh",
                  "iii",
                  "jjj",
                  "kkk"
                ]

                TOML_WRAP,
            <<<'TOML_WRAP'
                items = ["aaa", "bbb", "ccc", "ddd", "eee", "fff", "ggg", "hhh", "iii", "jjj", "kkk"]
                TOML_WRAP,
        ];
    }
}
