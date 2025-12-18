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
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\LintMdFixer>
 */
final class LintMdFixerTest extends AbstractFixerTestCase
{
    /**
     * @return iterable<int|string, array{0: string, 1?: null|string, 2?: array<string, mixed>}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'default' => [
            <<<'MD_WRAP'
                ## 全角数字

                > 这件蛋糕只卖 1000 元。
                MD_WRAP,
            <<<'MD_WRAP'
                ## 全角数字

                > 这件蛋糕只卖 １０００ 元。
                MD_WRAP,
        ];
    }
}
