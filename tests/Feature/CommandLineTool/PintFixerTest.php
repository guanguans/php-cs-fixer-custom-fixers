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
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\PintFixer>
 */
final class PintFixerTest extends AbstractFixerTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (\PHP_VERSION_ID < 80200) {
            self::markTestSkipped('The Pint fixer test requires PHP 8.2 or higher.');
        }
    }

    /**
     * @return iterable<int|string, array{0: string, 1?: null|string, 2?: array<string, mixed>}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'default' => [
            <<<'PHP_WRAP'
                <?php

                if (! $isFormatted) {

                }

                PHP_WRAP,
            <<<'PHP_WRAP'
                <?php

                if (!$isFormatted) {

                }
                PHP_WRAP,
        ];
    }
}
