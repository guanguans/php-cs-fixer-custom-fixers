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
 * Copyright (c) 2025-2026 guanguans<ityaozm@gmail.com>
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
use PhpCsFixer\ConfigurationException\RequiredFixerConfigurationException;
use PhpCsFixer\Tokenizer\Tokens;

it('will throw `RequiredFixerConfigurationException` for empty command', function (): void {
    $fixer = DotenvLinterFixer::make();
    $fixer->configure([
        AbstractCommandLineToolFixer::COMMAND => [],
    ]);
})
    ->group(__DIR__, __FILE__)
    ->throws(
        RequiredFixerConfigurationException::class,
        '[Guanguans/dotenv_linter] Missing required configuration: Invalid configuration for command, it must not be empty.'
    );

it('will throw `ProcessFailedException` for invalid command', function (): void {
    $fixer = DotenvLinterFixer::make();
    $fixer->configure([
        AbstractCommandLineToolFixer::COMMAND => ['invalid-command'],
    ]);
    $fixer->fix($fixer->makeDummySplFileInfo(), Tokens::fromCode(fake()->text()));
})
    ->group(__DIR__, __FILE__)
    ->throws(ProcessFailedException::class, 'invalid-command');

it('will throw `InvalidFixerConfigurationException` for invalid options item key', function (): void {
    $fixer = DotenvLinterFixer::make();
    $fixer->configure([
        AbstractCommandLineToolFixer::OPTIONS => [
            '--plain',
            'plain' => true,
        ],
    ]);
    $fixer->fix($fixer->makeDummySplFileInfo(), Tokens::fromCode(fake()->text()));
})
    ->group(__DIR__, __FILE__)
    ->throws(
        InvalidFixerConfigurationException::class,
        '[Guanguans/dotenv_linter] Invalid configuration for options item key [0], it must start with [-] or [--].'
    );

it('will throw `InvalidFixerConfigurationException` for invalid options item type', function (): void {
    $fixer = DotenvLinterFixer::make();
    $fixer->configure([
        AbstractCommandLineToolFixer::OPTIONS => [
            /** @see AbstractCommandLineToolFixer::normalizeOptionValue() */
            '--array' => fake()->words(),
            '--boolean' => fake()->boolean(),
            '--closure' => fn (DotenvLinterFixer $fixer): string => $fixer->getTemporaryMirrorFilePath(),
            '--float' => fake()->randomFloat(),
            '--integer' => fake()->randomDigit(),
            '--invalid' => new stdClass,
            '--null' => null,
            '--string' => fake()->word(),
            '--stringable' => new class {
                public function __toString(): string
                {
                    return fake()->word();
                }
            },
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
    Utils::dummyDebug();
    $fixer = DotenvLinterFixer::make();
    $fixer->configure([
        AbstractCommandLineToolFixer::OPTIONS => [
            '--dry-run' => null,
            '--plain' => false,
        ],
    ]);
    $fixer->fix($fixer->makeDummySplFileInfo(), Tokens::fromCode(fake()->text()));

    expect($fixer)->toBeInstanceOf(DotenvLinterFixer::class);
})->group(__DIR__, __FILE__);

it('can not debug process', function (): void {
    Utils::dummyNotTxtFormat();
    $fixer = DotenvLinterFixer::make();
    $fixer->configure([
        AbstractCommandLineToolFixer::OPTIONS => [
            '--dry-run' => null,
            '--plain' => false,
        ],
    ]);
    $fixer->fix($fixer->makeDummySplFileInfo(), Tokens::fromCode(fake()->text()));

    expect($fixer)->toBeInstanceOf(DotenvLinterFixer::class);
})->group(__DIR__, __FILE__)->depends('it can debug process');
