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

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DotenvLinterFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\CandidateOfAny;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\LowestPriority;
use PhpCsFixer\ConfigurationException\RequiredFixerConfigurationException;
use PhpCsFixer\Tokenizer\Tokens;

uses(
    CandidateOfAny::class,
    LowestPriority::class,
);

it('will throw `RequiredFixerConfigurationException` for empty extensions', function (): void {
    $fixer = DotenvLinterFixer::make();
    $fixer->configure([
        AbstractInlineHtmlFixer::EXTENSIONS => [],
    ]);
    $fixer->extensions();
})
    ->group(__DIR__, __FILE__)
    ->throws(
        RequiredFixerConfigurationException::class,
        '[Guanguans/dotenv_linter] Missing required configuration: Invalid configuration for extensions, it must not be empty.'
    );

it('can using concerns', function (): void {
    expect($this)
        ->isCandidate(Tokens::fromCode(fake()->text()))->toBeTrue()
        ->getPriority()->toBe(-\PHP_INT_MAX);

    $fixer = DotenvLinterFixer::make();
    expect($fixer)
        ->fix($fixer->makeDummySplFileInfo(), Tokens::fromCode(fake()->text()))->toBeNull()
        ->getName()->toBe(DotenvLinterFixer::name())
        ->getFile()->toBeInstanceOf(SplFileInfo::class)
        ->getTokens()->toBeInstanceOf(Tokens::class);
})->group(__DIR__, __FILE__);
