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

use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer;
use Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractFixerTestCase;
use Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractSpecificFixerTestCase;

/**
 * @internal
 *
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\TextlintFixer>
 */
final class TextlintFixerTest extends AbstractSpecificFixerTestCase
{
    /** @var array<string, true> */
    protected array $allowedFixersWithoutDefaultCodeSample = [
        'Guanguans/textlint' => true,
    ];

    /**
     * @return iterable<int|string, array{0: string, 1?: null|string, 2?: array<string, mixed>}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'default' => [
            <<<'TXT_WRAP'
                jQuery is JavaScript library.
                TXT_WRAP,
            <<<'TXT_WRAP'
                jquery is javascript library.
                TXT_WRAP,
            [AbstractCommandLineToolFixer::OPTIONS => ['--rule' => 'terminology']],
        ];
    }
}
