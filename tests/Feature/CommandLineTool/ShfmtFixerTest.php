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
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ShfmtFixer>
 */
final class ShfmtFixerTest extends AbstractFixerTestCase
{
    /**
     * @return iterable<int|string, array{0: string, 1?: null|string, 2?: array<string, mixed>}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'default' => [
            <<<'SH_WRAP'
                #!/bin/bash

                if foo; then
                	bar
                fi

                for i in 1 2 3; do
                	bar
                done

                SH_WRAP,
            <<<'SH_WRAP'
                #!/bin/bash

                if foo ;   then
                    bar
                fi

                for i in 1 2 3; do
                            bar
                done
                SH_WRAP,
        ];
    }
}
