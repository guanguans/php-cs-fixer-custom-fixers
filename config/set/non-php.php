<?php

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
use PhpCsFixer\Fixer\Basic\EncodingFixer;
use PhpCsFixer\Fixer\Basic\NonPrintableCharacterFixer;
use PhpCsFixer\Fixer\Whitespace\NoTrailingWhitespaceFixer;
use PhpCsFixer\Fixer\Whitespace\NoWhitespaceInBlankLineFixer;
use PhpCsFixer\Fixer\Whitespace\SingleBlankLineAtEofFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withCache(\sprintf('%s/.build/ecs/%s/', getcwd(), pathinfo(__FILE__, \PATHINFO_FILENAME)))
    ->withFileExtensions(Fixers::make()->extensions())
    ->withParallel()
    // ->withoutParallel()
    ->withPaths(Utils::defaultNonPhpPaths())
    ->withRules([
        /** @see \Symplify\EasyCodingStandard\ValueObject\Set\SetList::SPACES */
        EncodingFixer::class,
        NoTrailingWhitespaceFixer::class,
        NoWhitespaceInBlankLineFixer::class,
        NonPrintableCharacterFixer::class,
        SingleBlankLineAtEofFixer::class,
    ])
    // ->withConfiguredRule(AggregateCommandLineToolFixer::class, [
    //     AggregateCommandLineToolFixer::CONFIGURATIONS => [
    //         [
    //             /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DockerfmtFixer */
    //             AbstractCommandLineToolFixer::COMMAND => ['dockerfmt'],
    //             AbstractCommandLineToolFixer::OPTIONS => ['--newline' => true, '--space-redirects' => true, '--write' => true],
    //             AbstractInlineHtmlFixer::EXTENSIONS => ['Dockerfile'],
    //         ],
    //         [
    //             /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DotenvLinterFixer */
    //             AbstractCommandLineToolFixer::COMMAND => ['dotenv-linter', 'fix'],
    //             // AbstractCommandLineToolFixer::OPTIONS => [],
    //             AbstractInlineHtmlFixer::EXTENSIONS => ['env', 'env.example'],
    //         ],
    //         [
    //             /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ShfmtFixer */
    //             AbstractCommandLineToolFixer::COMMAND => ['shfmt'],
    //             AbstractCommandLineToolFixer::OPTIONS => ['--write' => true],
    //             AbstractInlineHtmlFixer::EXTENSIONS => ['sh', 'bats'],
    //             AggregateCommandLineToolFixer::IS_POST_TEMPORARY_MIRROR_FILE_PATH_COMMAND => true,
    //         ],
    //     ],
    // ])
    // ->withConfiguredRule(BladeFormatterFixer::class, [
    //     AbstractCommandLineToolFixer::COMMAND => ['path/to/node', 'path/to/blade-formatter'],
    //     AbstractCommandLineToolFixer::OPTIONS => [
    //         '--config' => 'path/to/.bladeformatterrc',
    //         '--extra-liners' => true,
    //         '--indent-size' => 2,
    //         // ...
    //     ],
    // ])
    // ->withConfiguredRule(SqlfluffFixer::class, [
    //     AbstractCommandLineToolFixer::OPTIONS => [
    //         '--dialect' => 'mysql',
    //     ],
    //     AbstractCommandLineToolFixer::EXTENSIONS => ['sql'],
    // ])
    ->withRules([
        AutocorrectFixer::class,
        LintMdFixer::class,
        // MarkdownlintCli2Fixer::class,
        MarkdownlintFixer::class,
        // TextlintFixer::class,
        ZhlintFixer::class,

        // PintFixer::class,
        BladeFormatterFixer::class,

        SqlOfDoctrineSqlFormatterFixer::class,
        // SqlOfPhpmyadminSqlParserFixer::class,
        // SqruffFixer::class,
        // SqlfluffFixer::class,

        DockerfmtFixer::class,
        DotenvLinterFixer::class,
        JsonFixer::class,
        ShfmtFixer::class,
        TombiFixer::class,
        TyposFixer::class,
        XmllintFixer::class,
        YamlfmtFixer::class,
    ]);
