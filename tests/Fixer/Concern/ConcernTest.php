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
use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AutocorrectFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\GenericsFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\CandidateOfAny;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\LowestPriority;
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;
use PhpCsFixer\Tokenizer\Tokens;

it('will throw `InvalidFixerConfigurationException` for empty extensions', function (): void {
    $fixer = new class('dotenv-linter') extends GenericsFixer {};
    $fixer->fix($fixer->makeDummySplFileInfo(), Tokens::fromCode(fake()->text()));
})
    ->group(__DIR__, __FILE__)
    ->throws(
        InvalidFixerConfigurationException::class,
        '[Guanguans/dotenv_linter] Invalid configuration for extensions, it must not be empty.'
    );

it('can using concerns', function (): void {
    $fixer = new class('dotenv-linter') extends GenericsFixer {
        use CandidateOfAny;
        use LowestPriority;
    };
    $fixer->configure([
        AbstractCommandLineToolFixer::COMMAND => ['dotenv-linter', 'fix'],
        AbstractInlineHtmlFixer::EXTENSIONS => ['env', 'env.example'],
    ]);
    $fixer->fix($fixer->makeDummySplFileInfo('md'), Tokens::fromCode(fake()->text()));
    $fixer->fix($fixer->makeDummySplFileInfo(), Tokens::fromCode(fake()->text()));

    expect(AutocorrectFixer::name())->toBe(AutocorrectFixer::make()->getName());
    expect($fixer)
        ->getFile()->toBeInstanceOf(SplFileInfo::class)
        ->getTokens()->toBeInstanceOf(Tokens::class)
        ->getPriority()->toBe(-\PHP_INT_MAX);
})->group(__DIR__, __FILE__);

it('can make dummy `SplFileInfo`', function (): void {
    Utils::dummyRun();
    expect(AutocorrectFixer::make())->makeDummySplFileInfo()->toBeInstanceOf(SplFileInfo::class);
})->group(__DIR__, __FILE__);
