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

use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\PintFixer;
use PhpCsFixer\FixerDefinition\FixerDefinition;

it('can get definition', function (): void {
    expect(new PintFixer)->getDefinition()->toBeInstanceOf(FixerDefinition::class);
})->group(__DIR__, __FILE__);

it('can get required options', function (): void {
    expect((fn (): array => $this->requiredOptions())->call(new PintFixer))
        ->toBeArray()
        ->not->toBeEmpty();
})->group(__DIR__, __FILE__);
