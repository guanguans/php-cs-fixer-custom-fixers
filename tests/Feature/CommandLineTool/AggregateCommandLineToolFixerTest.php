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
use Illuminate\Support\Collection;

/**
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AggregateCommandLineToolFixer>
 *
 * @internal
 *
 * @property array{
 *     command: list<string>,
 *     options: array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>,
 *     cwd: ?string,
 *     env: array<string, string>,
 *     input: ?string,
 *     timeout: null|float|int,
 *     extensions: list<string>,
 * } $configuration
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
            <<<'TOML_WRAP'
                and
                number
                style
                TOML_WRAP,
            <<<'TOML_WRAP'
                nd
                numer
                styl
                TOML_WRAP,
            [
                AggregateCommandLineToolFixer::CONFIGURATIONS => [
                    [
                        /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ShfmtFixer */
                        AbstractCommandLineToolFixer::COMMAND => ['shfmt'],
                        AbstractCommandLineToolFixer::OPTIONS => ['--write' => true],
                        AbstractInlineHtmlFixer::EXTENSIONS => [/** @see SortListItemOfSameScalarTypeRector */ 'sh', 'bats'],
                        AggregateCommandLineToolFixer::IS_POST_TEMPORARY_MIRROR_FILE_PATH_COMMAND => true,
                    ],
                    [
                        /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\TyposFixer */
                        AbstractCommandLineToolFixer::COMMAND => ['typos'],
                        AbstractCommandLineToolFixer::OPTIONS => [
                            // When using `Process` to run typos,
                            // it seems that it cannot find the configuration file in the current working directory.
                            // So we need to specify the configuration file explicitly.
                            '--config' => fn () => collect(['.typos.toml', '_typos.toml', 'typos.toml'])
                                ->when(
                                    $this->configuration[AbstractCommandLineToolFixer::CWD],
                                    static fn (Collection $configFiles, string $cwd): Collection => $configFiles->map(
                                        static fn (string $configFile): string => $cwd.\DIRECTORY_SEPARATOR.$configFile
                                    )
                                )
                                ->first(
                                    static fn (string $configFile): bool => is_file($configFile),
                                    false
                                ),
                            // Dry run mode will create temporary files, so we need to skip filename checks.
                            // '--no-check-filenames' => Utils::isDryRun(),
                            '--no-check-filenames' => true,
                            '--write-changes' => true,
                        ],
                        AbstractInlineHtmlFixer::EXTENSIONS => [/** @see SortListItemOfSameScalarTypeRector */ '*'],
                    ],
                    [
                        /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ZhlintFixer */
                        AbstractCommandLineToolFixer::COMMAND => ['zhlint'],
                        AbstractCommandLineToolFixer::OPTIONS => ['--fix' => true],
                        AbstractInlineHtmlFixer::EXTENSIONS => [/** @see SortListItemOfSameScalarTypeRector */ 'txt', 'text', 'md', 'markdown'],
                        AggregateCommandLineToolFixer::IS_TEMPORARY_MIRROR_FILE_CREATED_IN_ORIGINAL_DIRECTORY => true,
                    ],
                ],
            ],
        ];
    }
}
