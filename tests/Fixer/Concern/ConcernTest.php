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
use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AutocorrectFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\GenericsFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\CandidateOfAny;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\LowestPriority;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\SupportsOfPathArg;
use PhpCsFixer\Tokenizer\Tokens;

it('can throws `InvalidFixerConfigurationException`', function (): void {
    $fixer = new class('dotenv-linter') extends GenericsFixer {};
    $fixer->fix($fixer->makeDummySplFileInfo(), Tokens::fromCode(fake()->text()));
})
    ->group(__DIR__, __FILE__)
    ->throws(
        InvalidFixerConfigurationException::class,
        '[Guanguans/dotenv_linter] Invalid configuration for extensions, it must not be empty.'
    );

it('can candidate of any', function (): void {
    $fixer = new class('dotenv-linter') extends GenericsFixer {
        use CandidateOfAny;
        use LowestPriority;
        use SupportsOfPathArg;
    };
    $fixer->configure([
        AbstractCommandLineToolFixer::COMMAND => ['dotenv-linter', 'fix'],
        AbstractInlineHtmlFixer::EXTENSIONS => ['env', 'env.example'],
    ]);
    $fixer->fix($fixer->makeDummySplFileInfo(), Tokens::fromCode(fake()->text()));
    expect($fixer)->getPriority()->toBe(-\PHP_INT_MAX);
    expect(AutocorrectFixer::name())->toBe(AutocorrectFixer::make()->getName());
})->group(__DIR__, __FILE__);
