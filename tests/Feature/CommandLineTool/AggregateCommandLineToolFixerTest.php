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

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AggregateCommandLineToolFixer;
use Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractFixerTestCase;
use Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractSpecificFixerTestCase;

/**
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AggregateCommandLineToolFixer>
 *
 * @internal
 */
final class AggregateCommandLineToolFixerTest extends AbstractSpecificFixerTestCase
{
    /**
     * @return iterable<int|string, array{0: string, 1?: null|string, 2?: array<string, mixed>}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'default' => [
            /** @see SortListItemOfSameScalarTypeRector */
            <<<'ENV_WRAP'
                BAR=FOO
                FOO=BAR

                ENV_WRAP,
            <<<'ENV_WRAP'
                FOO= BAR
                BAR = FOO
                ENV_WRAP,
            [
                AggregateCommandLineToolFixer::CONFIGURATIONS => [
                    [
                        /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DockerfmtFixer */
                        AbstractCommandLineToolFixer::COMMAND => ['dockerfmt'],
                        AbstractCommandLineToolFixer::OPTIONS => ['--newline' => true, '--space-redirects' => true, '--write' => true],
                        AbstractInlineHtmlFixer::EXTENSIONS => ['Dockerfile'],
                    ],
                    [
                        /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DotenvLinterFixer */
                        AbstractCommandLineToolFixer::COMMAND => ['dotenv-linter', 'fix'],
                        // AbstractCommandLineToolFixer::OPTIONS => [],
                        AbstractInlineHtmlFixer::EXTENSIONS => ['env', 'env.example'],
                    ],
                    [
                        /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ShfmtFixer */
                        AbstractCommandLineToolFixer::COMMAND => ['shfmt'],
                        AbstractCommandLineToolFixer::OPTIONS => ['--write' => true],
                        AbstractInlineHtmlFixer::EXTENSIONS => ['bats', 'sh'],
                        AggregateCommandLineToolFixer::IS_POST_TEMPORARY_MIRROR_FILE_PATH_COMMAND => true,
                    ],
                ],
            ],
        ];
    }
}
