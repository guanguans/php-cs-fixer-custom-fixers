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

use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AutocorrectFixer;
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;

it('is sequential', function (): void {
    if (Utils::isDryRun()) {
        $_SERVER['argv'] = array_filter(
            $_SERVER['argv'],
            static fn ($value): bool => '--dry-run' !== $value,
        );
    }

    expect(AutocorrectFixer::make())->makeDummySplFileInfo()->toBeInstanceOf(SplFileInfo::class);
})->group(__DIR__, __FILE__);
