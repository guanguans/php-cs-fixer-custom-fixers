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

use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AggregateCommandLineToolFixer;
use Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractFixerTestCase;
use Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractSpecificFixerTestCase;

/**
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AggregateCommandLineToolFixer>
 *
 * @internal
 *
 * @property array{
 *     command: list<string>,
 *     options: array<string, null|(callable&\JsonSerializable)|list<null|(callable&\JsonSerializable)|scalar>|scalar>,
 *     cwd: ?string,
 *     env: array<string, string>,
 *     input: ?string,
 *     timeout: null|float|int,
 *     extensions: non-empty-list<string>,
 * } $configuration
 */
final class AggregateCommandLineToolFixerTest extends AbstractSpecificFixerTestCase
{
    /**
     * @return iterable<int|string, array{0: string, 1?: null|string, 2?: array<string, mixed>}>
     *
     * @noinspection PhpForeachOverSingleElementArrayLiteralInspection
     */
    public static function provideFixCases(): iterable
    {
        /** @var class-string<AbstractSpecificFixerTestCase> $fixerTestClass */
        foreach ([/* ShfmtFixerTest::class, */ TyposFixerTest::class/* , ZhlintFixerTest::class */] as $fixerTestClass) {
            foreach ($fixerTestClass::provideFixCases() as $name => [$expected, $input]) {
                yield "$fixerTestClass:$name" => [$expected, $input, AggregateCommandLineToolFixer::configurationSample()];
            }
        }
    }
}
