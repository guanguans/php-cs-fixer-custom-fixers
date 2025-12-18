<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

namespace Guanguans\PhpCsFixerCustomFixersTests\Feature\CommandLineTool;

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\GenericsFixer;
use Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractFixerTestCase;

/**
 * @internal
 *
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\GenericsFixer>
 */
final class GenericsFixerTest extends AbstractFixerTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        self::markTestSkipped('GenericsFixer test.');
    }

    /**
     * @return iterable<int|string, array{0: string, 1?: null|string, 2?: array<string, mixed>}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'default' => [
            <<<'ENV_WRAP'
                BAR=FOO
                FOO=BAR

                ENV_WRAP,
            <<<'ENV_WRAP'
                FOO= BAR
                BAR = FOO
                ENV_WRAP,
            self::configuration(),
        ];
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     * @noinspection MissingParentCallInspection
     */
    protected function createFixer(): GenericsFixer
    {
        $genericsFixer = new GenericsFixer('dotenv-linter');
        $genericsFixer->configure(self::configuration());

        return $genericsFixer;
    }

    /**
     * @return array{
     *     command: list<string>,
     *     options: array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>,
     *     cwd: ?string,
     *     env: array<string, string>,
     *     input: ?string,
     *     timeout: null|float|int,
     *     extensions: list<string>,
     * } $configuration
     */
    private static function configuration(): array
    {
        return [
            AbstractCommandLineToolFixer::COMMAND => ['dotenv-linter', 'fix'],
            AbstractInlineHtmlFixer::EXTENSIONS => ['env', 'env.example'],
        ];
    }
}
