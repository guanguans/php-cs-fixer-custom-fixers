<?php

/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AggregateCommandLineToolFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AutocorrectFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\BladeFormatterFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DockerfmtFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DotenvLinterFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\LintMdFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\MarkdownlintCli2Fixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\MarkdownlintFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\PintFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ShfmtFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\SqlfluffFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\SqruffFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\TextlintFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\TombiFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\TyposFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\XmllintFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\YamlfmtFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ZhlintFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\InlineHtml\JsonFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\InlineHtml\SqlOfDoctrineSqlFormatterFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\InlineHtml\SqlOfPhpmyadminSqlParserFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixers;
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;
use Illuminate\Support\Collection;
use PhpCsFixer\Config;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

return (new Config)
    ->registerCustomFixers($fixers = Fixers::make())
    ->setRules([
        'encoding' => true,
        'no_trailing_whitespace' => true,
        'no_whitespace_in_blank_line' => true,
        'non_printable_character' => true,
        'single_blank_line_at_eof' => true,

        // AggregateCommandLineToolFixer::name() => [
        //     AggregateCommandLineToolFixer::CONFIGURATIONS => [
        //         [
        //             /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ShfmtFixer */
        //             AbstractCommandLineToolFixer::COMMAND => ['shfmt'],
        //             AbstractCommandLineToolFixer::OPTIONS => ['--write' => true],
        //             AbstractInlineHtmlFixer::EXTENSIONS => [/** @see SortListItemOfSameScalarTypeRector */ 'sh', 'bats'],
        //             AggregateCommandLineToolFixer::IS_POST_TEMPORARY_MIRROR_FILE_PATH_COMMAND => true,
        //         ],
        //         [
        //             /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\TyposFixer */
        //             AbstractCommandLineToolFixer::COMMAND => ['typos'],
        //             AbstractCommandLineToolFixer::OPTIONS => [
        //                 // When using `Process` to run typos,
        //                 // it seems that it cannot find the configuration file in the current working directory.
        //                 // So we need to specify the configuration file explicitly.
        //                 '--config' => fn () => collect(['.typos.toml', '_typos.toml', 'typos.toml'])
        //                     ->when(
        //                         $this->configuration[AbstractCommandLineToolFixer::CWD],
        //                         static fn (Collection $configFiles, string $cwd): Collection => $configFiles->map(
        //                             static fn (string $configFile): string => $cwd.\DIRECTORY_SEPARATOR.$configFile
        //                         )
        //                     )
        //                     ->first(
        //                         static fn (string $configFile): bool => is_file($configFile),
        //                         false
        //                     ),
        //                 // Dry run mode will create temporary files, so we need to skip filename checks.
        //                 // '--no-check-filenames' => Utils::isDryRun(),
        //                 '--no-check-filenames' => true,
        //                 '--write-changes' => true,
        //             ],
        //             AbstractInlineHtmlFixer::EXTENSIONS => [/** @see SortListItemOfSameScalarTypeRector */ '*'],
        //         ],
        //         [
        //             /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ZhlintFixer */
        //             AbstractCommandLineToolFixer::COMMAND => ['zhlint'],
        //             AbstractCommandLineToolFixer::OPTIONS => ['--fix' => true],
        //             AbstractInlineHtmlFixer::EXTENSIONS => [/** @see SortListItemOfSameScalarTypeRector */ 'txt', 'text', 'md', 'markdown'],
        //             AggregateCommandLineToolFixer::IS_TEMPORARY_MIRROR_FILE_CREATED_IN_ORIGINAL_DIRECTORY => true,
        //         ],
        //     ],
        // ],

        AutocorrectFixer::name() => true,
        LintMdFixer::name() => true,
        // MarkdownlintCli2Fixer::name() => true,
        MarkdownlintFixer::name() => true,
        // TextlintFixer::name() => true,
        ZhlintFixer::name() => true,

        // PintFixer::name() => true,
        // BladeFormatterFixer::name() => [ // Custom BladeFormatterFixer configuration
        //     AbstractCommandLineToolFixer::COMMAND => ['path/to/node', 'path/to/blade-formatter'],
        //     AbstractCommandLineToolFixer::OPTIONS => [
        //         '--config' => 'path/to/.bladeformatterrc',
        //         '--extra-liners' => true,
        //         '--indent-size' => 2,
        //         // ...
        //     ],
        // ],
        BladeFormatterFixer::name() => true, // Default BladeFormatterFixer configuration

        SqlOfDoctrineSqlFormatterFixer::name() => true,
        // SqlOfPhpmyadminSqlParserFixer::name() => true,
        // SqruffFixer::name() => true,
        // SqlfluffFixer::name() => true,
        // SqlfluffFixer::name() => [
        //     AbstractCommandLineToolFixer::OPTIONS => [
        //         '--dialect' => 'mysql',
        //     ],
        //     AbstractCommandLineToolFixer::EXTENSIONS => ['sql'],
        // ],

        DockerfmtFixer::name() => true,
        DotenvLinterFixer::name() => true,
        JsonFixer::name() => true,
        ShfmtFixer::name() => true,
        TombiFixer::name() => true,
        TyposFixer::name() => true,
        XmllintFixer::name() => true,
        YamlfmtFixer::name() => true,
    ])
    ->setFinder(
        Utils::defaultNonPhpFinder()
        // ->name(['/\.sh$/', '/\.bats$/', '/\.*$/', '/\.txt$/', '/\.text$/', '/\.md$/', '/\.markdown$/'])
    )
    ->setCacheFile(\sprintf('%s/.build/php-cs-fixer/%s.cache', getcwd(), pathinfo(__FILE__, \PATHINFO_FILENAME)))
    // ->setParallelConfig(ParallelConfigFactory::sequential())
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRiskyAllowed(true)
    ->setUnsupportedPhpVersionAllowed(true)
    ->setUsingCache(true);
