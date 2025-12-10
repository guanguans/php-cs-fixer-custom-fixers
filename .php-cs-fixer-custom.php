<?php

/** @noinspection PhpUnusedAliasInspection */

declare(strict_types=1);

/**
 * Copyright (c) 2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AutocorrectFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\BladeFormatterFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DockerFmtFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DotenvLinterFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\LintMdFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\MarkdownLintCli2Fixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\MarkdownLintFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\PintFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ShfmtFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\SqlFluffFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\SqRuffFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\TextLintFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\TombiFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\XmlLintFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\YamlFmtFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ZhLintFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\InlineHtml\DoctrineSqlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\InlineHtml\JsonFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\InlineHtml\PhpMyAdminSqlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixers;
use PhpCsFixer\Config;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use Symfony\Component\Finder\Finder;

// putenv('PHP_CS_FIXER_ENFORCE_CACHE=1');
// putenv('PHP_CS_FIXER_IGNORE_ENV=1');
putenv('PHP_CS_FIXER_FUTURE_MODE=1');
putenv('PHP_CS_FIXER_NON_MONOLITHIC=1');
putenv('PHP_CS_FIXER_PARALLEL=1');

/**
 * @see https://github.com/laravel/pint/blob/main/resources/presets
 */
return (new Config)
    ->registerCustomFixers($fixers = (new Fixers))
    ->setRules([
        'encoding' => true,
        'no_trailing_whitespace' => true,
        'no_whitespace_in_blank_line' => true,
        'non_printable_character' => true,
        'single_blank_line_at_eof' => true,

        AutocorrectFixer::name() => true,
        LintMdFixer::name() => true,
        // MarkdownLintCli2Fixer::name() => true,
        // MarkdownLintFixer::name() => true,
        // TextLintFixer::name() => true,
        ZhLintFixer::name() => true,

        // PintFixer::name() => true,
        BladeFormatterFixer::name() => true,

        DoctrineSqlFixer::name() => true,
        // PhpMyAdminSqlFixer::name() => true,
        // SqRuffFixer::name() => true,
        // SqlFluffFixer::name() => true,
        // SqlFluffFixer::name() => [
        //     AbstractCommandLineToolFixer::OPTIONS => [
        //         '--dialect' => 'mysql',
        //     ],
        //     SqlFluffFixer::EXTENSIONS => ['sql'],
        // ],

        DockerFmtFixer::name() => true,
        DotenvLinterFixer::name() => true,
        JsonFixer::name() => true,
        ShfmtFixer::name() => true,
        TombiFixer::name() => true,
        XmlLintFixer::name() => true,
        YamlFmtFixer::name() => true,
    ])
    ->setFinder(
        Finder::create()
            ->in(__DIR__)
            ->exclude([
                'Fixtures/',
                'vendor-bin/',
                'vendor/',
            ])
            ->notPath([
                '.chglog/CHANGELOG.tpl.md',
                'CHANGELOG.md',
                'composer.json',
                'phpunit.xml.dist',
                'README.md',
            ])
            ->name(array_merge(...array_map(
                static fn (FixerInterface $fixer): array => array_map(
                    static fn (string $ext): string => \sprintf('/\.%s$/', str_replace('.', '\.', $ext)),
                    method_exists($fixer, 'extensions') ? $fixer->extensions() : [],
                ),
                iterator_to_array($fixers),
            )))
            ->notName([
                '/\-overview\.md$/',
                '/\.lock$/',
                '/\-lock\.json$/',
                // '/\.php$/',
                '/(?<!\.blade)\.php$/',
            ])
            ->ignoreDotFiles(false)
            ->ignoreUnreadableDirs(false)
            ->ignoreVCS(true)
            ->ignoreVCSIgnored(true)
            ->files()
    )
    ->setCacheFile(\sprintf('%s/.build/php-cs-fixer/%s.cache', __DIR__, pathinfo(__FILE__, \PATHINFO_FILENAME)))
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRiskyAllowed(true)
    ->setUnsupportedPhpVersionAllowed(true)
    ->setUsingCache(true);
