<?php

/** @noinspection AnonymousFunctionStaticInspection */
/** @noinspection NullPointerExceptionInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpVoidFunctionResultUsedInspection */
/** @noinspection SqlResolve */
/** @noinspection StaticClosureCanBeUsedInspection */
declare(strict_types=1);

/**
 * Copyright (c) 2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

use Guanguans\PhpCsFixerCustomFixers\Exception\InvalidFixerConfigurationException;
use Guanguans\PhpCsFixerCustomFixers\Exception\ProcessFailedException;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DotenvLinterFixer;
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;
use PhpCsFixer\Tokenizer\Tokens;
use Symfony\Component\Console\Input\ArgvInput;

it('throw `InvalidFixerConfigurationException` when call `fix`', function (): void {
    $fixer = new DotenvLinterFixer;
    $fixer->configure([
        AbstractCommandLineToolFixer::COMMAND => [],
    ]);
    $fixer->fix($fixer->makeDummySplFileInfo(), Tokens::fromCode(fake()->text()));
})
    ->group(__DIR__, __FILE__)
    ->throws(InvalidFixerConfigurationException::class, '[Guanguans/dotenv_linter] Invalid configuration for command, it must not be empty.');

it('throw `ProcessFailedException` when call `fix`', function (): void {
    $fixer = new DotenvLinterFixer;
    $fixer->configure([
        AbstractCommandLineToolFixer::COMMAND => ['fake'],
    ]);
    $fixer->fix($fixer->makeDummySplFileInfo(), Tokens::fromCode(fake()->text()));
})
    ->group(__DIR__, __FILE__)
    ->throws(ProcessFailedException::class, 'fake');

it('throw `InvalidFixerConfigurationException` of options when call `fix`', function (): void {
    $fixer = new DotenvLinterFixer;
    $fixer->configure([
        AbstractCommandLineToolFixer::OPTIONS => [
            'plain' => true,
        ],
    ]);
    $fixer->fix($fixer->makeDummySplFileInfo(), Tokens::fromCode(fake()->text()));
})
    ->group(__DIR__, __FILE__)
    ->throws(
        InvalidFixerConfigurationException::class,
        '[Guanguans/dotenv_linter] Invalid configuration for options item key [plain], it must start with [-] or [--].'
    );

it('throw `InvalidFixerConfigurationException` of options item type when call `fix`', function (): void {
    $fixer = new DotenvLinterFixer;
    $fixer->configure([
        AbstractCommandLineToolFixer::OPTIONS => [
            '--plain' => false,
            '--exclude' => new class {
                public function __toString(): string
                {
                    return 'fake';
                }
            },
            '--ignore-checks' => fn (DotenvLinterFixer $fixer): string => $fixer->getFilePath(),
            '--recursive' => [
                fake()->slug(),
                fake()->slug(),
                fake()->slug(),
            ],
            '--no-backup' => new stdClass,
        ],
    ]);
    $fixer->fix($fixer->makeDummySplFileInfo(), Tokens::fromCode(fake()->text()));
})
    ->group(__DIR__, __FILE__)
    ->throws(
        InvalidFixerConfigurationException::class,
        '[Guanguans/dotenv_linter] Invalid configuration for options item type [object].'
    );

it('can debug process', function (): void {
    if (!Utils::isDebug()) {
        $_SERVER['argv'][] = '-vvv';
        Utils::makeSymfonyStyle(new ArgvInput($_SERVER['argv']));
    }

    $fixer = new DotenvLinterFixer;
    $fixer->configure([
        AbstractCommandLineToolFixer::OPTIONS => [
            '--plain' => false,
        ],
    ]);
    $fixer->fix($fixer->makeDummySplFileInfo(), Tokens::fromCode(fake()->text()));

    expect($fixer)->toBeInstanceOf(DotenvLinterFixer::class);
})->group(__DIR__, __FILE__);
