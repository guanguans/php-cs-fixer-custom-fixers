<?php

/** @noinspection AnonymousFunctionStaticInspection */
/** @noinspection NullPointerExceptionInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpVoidFunctionResultUsedInspection */
/** @noinspection SqlResolve */
/** @noinspection StaticClosureCanBeUsedInspection */
/** @noinspection PhpMissingParentCallCommonInspection */
declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer;
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
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Process\ExecutableFinder;

// it('will throw `Error` when call private constructor', function (): void {
//     expect(new ReflectionClass(Utils::class))->newInstanceWithoutConstructor()->toBeInstanceOf(Utils::class);
//     new Utils;
// })
//     ->group(__DIR__, __FILE__)
//     ->throws(
//         Error::class,
//         \sprintf('Call to private %s::__construct() from ', Utils::class)
//     );

it('can get default non php paths', function (): void {
    expect(Utils::defaultNonPhpPaths())->toBeArray()->toBeTruthy();
})->group(__DIR__, __FILE__);

it('can get default paths', function (): void {
    expect(Utils::defaultPaths())->toBeArray()->toBeTruthy();
})->group(__DIR__, __FILE__);

it('can get default root directories', function (): void {
    expect(Utils::defaultRootDirectories())->toBeArray()->toBeTruthy();
})->group(__DIR__, __FILE__);

it('can get configuration of header comment fixer', function (): void {
    expect(Utils::configurationOfHeaderCommentFixer(
        'guanguans/php-cs-fixer-custom-fixers',
        '2025',
        __DIR__.'/../../LICENSE'
    ))->toBeArray()->toBeTruthy();
})->group(__DIR__, __FILE__);

it('can determine if a file is a json file', function (string $content, bool $expected): void {
    file_put_contents($file = Utils::createTemporaryFile(), $content);
    expect(Utils::isJsonFile(new SplFileInfo($file)))->toBe($expected);
})->group(__DIR__, __FILE__)->with([
    'empty file' => ['', false],
    'text file' => [fake()->word(), false],
    'json file' => [json_encode(fake()->words()), true],
]);

it('can determine if a file is a chinese text file', function (string $content, bool $expected): void {
    file_put_contents($file = Utils::createTemporaryFile(), $content);
    expect(Utils::isChineseTextFile(new SplFileInfo($file)))->toBe($expected);
})->group(__DIR__, __FILE__)->with([
    'empty file' => ['', false],
    'en_US text file' => [fake('en_US')->realText(10240), false],
    // 'zh_CN text file' => [fake('zh_CN')->realText(10240), true], /** @see vendor/fakerphp/faker/src/Faker/Provider/zh_CN/Text.php */
    'zh_TW text file' => [fake('zh_TW')->realText(10240), true], /** @see vendor/fakerphp/faker/src/Faker/Provider/zh_TW/Text.php */
    'en_US and zh_TW text file' => [fake('en_US')->realText(10240 / 4).\PHP_EOL.fake('zh_TW')->realText(10240 / 4), false],
    'zh_TW and en_US text file' => [fake('zh_TW')->realText(10240 / 4).\PHP_EOL.fake('en_US')->realText(10240 / 4), false],
    'en_US and zh_TW text file using specified threshold' => [fake('en_US')->realText(10240 / 4).\PHP_EOL.fake('zh_TW')->realText(10240 / 4 / 0.618), false],
    'zh_TW and en_US text file using specified threshold' => [fake('zh_TW')->realText(10240 / 4 / 0.618).\PHP_EOL.fake('en_US')->realText(10240 / 4), true],
    'en_US and zh_TW text file using default threshold' => [fake('en_US')->realText(10240 / 4).\PHP_EOL.fake('zh_TW')->realText(10240 / 4 / (0.618 * (0.43 / 0.5))), false],
    'zh_TW and en_US text file using default threshold' => [fake('zh_TW')->realText(10240 / 4 / (0.618 * (0.43 / 0.5))).\PHP_EOL.fake('en_US')->realText(10240 / 4), true],
]);

it('can determine if a file is a text file', function (string $content, bool $expected): void {
    file_put_contents($file = Utils::createTemporaryFile(), $content);
    expect(Utils::isTextFile(new SplFileInfo($file)))->toBe($expected);
})->group(__DIR__, __FILE__)->with([
    'empty file' => ['', false],
    'binary file' => ["\x00\x01\x02", false],
    'text file' => ['This is a text file.', true],
]);

it('can determine if a file is a text file with length', function (int $length): void {
    collect(['go', 'node', 'php'])
        ->map(fn (string $binary): ?string => (new ExecutableFinder)->find($binary))
        ->filter(fn (?string $binary): bool => null !== $binary && is_file($binary))
        ->each(fn (string $binary) => expect(Utils::isTextFile(new SplFileInfo($binary), $length))->toBeFalse());
})->group(__DIR__, __FILE__)->with([
    // '1 byte' => 1,
    '10 byte' => 10,
    '100 byte' => 100,
    '1000 byte' => 1000,
    '10000 byte' => 10000,
]);

it('return false for non-existent file or directory', function (string $filename): void {
    expect(Utils::isTextFile(new SplFileInfo($filename)))->toBeFalse();
})->group(__DIR__, __FILE__)->with([
    'non-existent file' => ['non-existent-file.txt'],
    'directory' => [__DIR__],
]);

it('return false for non-readable file', function (): void {
    file_put_contents($file = Utils::createTemporaryFile(), 'content');

    try {
        chmod($file, 0200);
        expect(Utils::isTextFile(new SplFileInfo($file)))->toBeFalse();
    } finally {
        chmod($file, 0644);
    }
})->group(__DIR__, __FILE__)->skip(windows_os());

it('return false when reading file content return false', function (): void {
    $fileInfo = new class(__FILE__) extends SplFileInfo {
        public function isReadable(): bool
        {
            return true;
        }

        public function isFile(): bool
        {
            return true;
        }

        public function openFile($mode = 'r', $useIncludePath = false, $context = null): SplFileObject
        {
            return new class('php://memory') extends SplFileObject {
                /**
                 * @param mixed $length
                 *
                 * @noinspection PhpLanguageLevelInspection
                 */
                #[\ReturnTypeWillChange]
                public function fread($length): bool
                {
                    return false;
                }
            };
        }
    };

    expect(Utils::isTextFile($fileInfo))->toBeFalse();
})->group(__DIR__, __FILE__);

it('can get first argument', function (): void {
    Utils::getFirstArgument();
})->group(__DIR__, __FILE__)->expectNotToPerformAssertions();

it('can make symfony style', function (): void {
    Utils::dummyDebug('--debug');
    expect(Utils::makeSymfonyStyle())
        ->toBe(Utils::makeSymfonyStyle())
        ->not->toBe(Utils::makeSymfonyStyle(new ArgvInput(array_merge($_SERVER['argv'], ['--fake-option']))));
})->group(__DIR__, __FILE__);

it('can stringify variable', function (): void {
    expect([
        Utils::toString(fake()->name()),
        Utils::toString(fake()->creditCardDetails()),
    ])->each->toBeString();
})->group(__DIR__, __FILE__);

it('can get first see doc for object or class', function (): void {
    expect(Utils::firstSeeDocFor(Utils::class))->toBeNull();

    expect(
        collect(Fixers::make())
            ->mapWithKeys(fn (AbstractFixer $fixer): array => [\get_class($fixer) => Utils::firstSeeDocFor($fixer)])
            ->all()
    )->toBe([
        AggregateCommandLineToolFixer::class => null,
        AutocorrectFixer::class => 'https://github.com/huacnlee/autocorrect',
        BladeFormatterFixer::class => 'https://github.com/shufo/blade-formatter',
        DockerfmtFixer::class => 'https://github.com/reteps/dockerfmt',
        DotenvLinterFixer::class => 'https://github.com/dotenv-linter/dotenv-linter',
        LintMdFixer::class => 'https://github.com/lint-md/lint-md',
        MarkdownlintCli2Fixer::class => 'https://github.com/DavidAnson/markdownlint-cli2',
        MarkdownlintFixer::class => 'https://github.com/igorshubovych/markdownlint-cli',
        PintFixer::class => 'https://github.com/laravel/pint',
        ShfmtFixer::class => 'https://github.com/mvdan/sh',
        SqlfluffFixer::class => 'https://github.com/sqlfluff/sqlfluff',
        SqruffFixer::class => 'https://github.com/quarylabs/sqruff',
        TextlintFixer::class => 'https://github.com/textlint/textlint',
        TombiFixer::class => 'https://github.com/tombi-toml/tombi',
        TyposFixer::class => 'https://github.com/crate-ci/typos',
        XmllintFixer::class => 'https://gnome.pages.gitlab.gnome.org/libxml2/xmllint.html',
        YamlfmtFixer::class => 'https://github.com/google/yamlfmt',
        ZhlintFixer::class => 'https://github.com/zhlint-project/zhlint',
        JsonFixer::class => 'https://www.php.net/manual/en/function.json-encode.php',
        SqlOfDoctrineSqlFormatterFixer::class => 'https://github.com/doctrine/sql-formatter',
        SqlOfPhpmyadminSqlParserFixer::class => 'https://github.com/phpmyadmin/sql-parser',
    ]);
})->group(__DIR__, __FILE__);
