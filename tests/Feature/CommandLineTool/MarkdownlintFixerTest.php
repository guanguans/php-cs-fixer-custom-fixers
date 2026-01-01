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
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\MarkdownlintFixer>
 *
 * @internal
 */
final class MarkdownlintFixerTest extends AbstractFixerTestCase
{
    /**
     * @return iterable<int|string, array{0: string, 1?: null|string, 2?: array<string, mixed>}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'default' => [
            <<<'MD_WRAP'
                # Examples

                ## This is ordered list

                1. First item
                2. Second item

                ## This is unordered list

                * https://link.com
                * [this is link](https://link.com   )
                * **bold text**

                MD_WRAP,
            <<<'MD_WRAP'
                # Examples
                ## This is ordered list

                1.    First item
                2. Second item

                ## This is unordered list

                * https://link.com
                * [ this is link  ](https://link.com   )
                * ** bold text **
                MD_WRAP,
        ];
    }
}
