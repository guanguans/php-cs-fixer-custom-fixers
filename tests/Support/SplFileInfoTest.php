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

use Guanguans\PhpCsFixerCustomFixers\Exception\RuntimeException;
use Guanguans\PhpCsFixerCustomFixers\Support\SplFileInfo;

it('will throw `RuntimeException` for not exits file', function (): void {
    (new SplFileInfo('non-existent-file'))->getContents();
})
    ->group(__DIR__, __FILE__)
    ->throws(
        RuntimeException::class,
        // 'file_get_contents(non-existent-file): failed to open stream: No such file or directory',
        // 'file_get_contents(non-existent-file): Failed to open stream: No such file or directory',
        'file_get_contents(non-existent-file): ',
    );
