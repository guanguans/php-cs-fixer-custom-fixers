<?php

/** @noinspection AnonymousFunctionStaticInspection */
/** @noinspection NullPointerExceptionInspection */
/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
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

use Guanguans\PhpCsFixerCustomFixers\Fixers;

it('can get dependency commands', function (): void {
    expect(Fixers::make())->dependencyCommands()->toBeArray()->toBeTruthy();
})->group(__DIR__, __FILE__);

it('can get dependency names', function (): void {
    expect(Fixers::make())->getDependencyNames()->toBeArray()->toBeTruthy();
})->group(__DIR__, __FILE__);

it('can get extension patterns', function (): void {
    expect(Fixers::make())->extensionPatterns()->toBeArray()->toBeTruthy();
})->group(__DIR__, __FILE__);

it('can get extensions', function (): void {
    expect(Fixers::make())->extensions()->toBeArray()->toBeTruthy();
})->group(__DIR__, __FILE__);
