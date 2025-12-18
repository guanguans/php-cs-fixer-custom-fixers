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

use Guanguans\PhpCsFixerCustomFixers\Fixers;

it('can get alias names', function (): void {
    expect(Fixers::make())
        ->getAliasNames()
        ->toBeArray()
        ->not->toBeEmpty();
})->group(__DIR__, __FILE__);

it('can get extension patterns', function (): void {
    expect(Fixers::make())
        ->extensionPatterns()
        ->toBeArray()
        ->not->toBeEmpty();
})->group(__DIR__, __FILE__);

it('can get extensions', function (): void {
    expect(Fixers::make())
        ->extensions()
        ->toBeArray()
        ->not->toBeEmpty();
})->group(__DIR__, __FILE__);
