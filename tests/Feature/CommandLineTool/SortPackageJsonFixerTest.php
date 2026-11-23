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

namespace Guanguans\PhpCsFixerCustomFixersTests\Feature\CommandLineTool;

use Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractFixerTestCase;

/**
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\SortPackageJsonFixer>
 *
 * @internal
 */
final class SortPackageJsonFixerTest extends AbstractFixerTestCase
{
    /**
     * @return iterable<int|string, array{0: string, 1?: null|string, 2?: array<string, mixed>}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'default' => [
            /** @see SortListItemOfSameScalarTypeRector */
            <<<'PACKAGE_JSON_WRAP'
                {
                    "$schema": "https://www.schemastore.org/package.json",
                    "private": true,
                    "type": "module",
                    "scripts": {
                        "build": "vite build",
                        "dev": "vite"
                    },
                    "devDependencies": {
                        "@tailwindcss/vite": "^4.0.0",
                        "concurrently": "^10.0.3",
                        "laravel-vite-plugin": "^3.1",
                        "tailwindcss": "^4.0.0",
                        "vite": "^8.0.0"
                    },
                    "optionalDependencies": {
                        "@laravel/multiplex": "^0.4.1"
                    }
                }

                PACKAGE_JSON_WRAP,
            <<<'PACKAGE_JSON_WRAP'
                {
                    "$schema": "https://www.schemastore.org/package.json",
                    "type": "module",
                    "private": true,
                    "scripts": {
                        "build": "vite build",
                        "dev": "vite"
                    },
                    "devDependencies": {
                        "@tailwindcss/vite": "^4.0.0",
                        "laravel-vite-plugin": "^3.1",
                        "concurrently": "^10.0.3",
                        "tailwindcss": "^4.0.0",
                        "vite": "^8.0.0"
                    },
                    "optionalDependencies": {
                        "@laravel/multiplex": "^0.4.1"
                    }
                }

                PACKAGE_JSON_WRAP,
        ];
    }
}
