<?php

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

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\GenericsFixer;
use Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractFixerTestCase;
use Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractSpecificFixerTestCase;

/**
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\GenericsFixer>
 *
 * @internal
 */
final class GenericsFixerTest extends AbstractSpecificFixerTestCase
{
    public const CONFIGURATION = [
        /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DotenvLinterFixer */
        AbstractCommandLineToolFixer::COMMAND => ['dotenv-linter', 'fix'],
        // AbstractCommandLineToolFixer::OPTIONS => [],
        AbstractInlineHtmlFixer::EXTENSIONS => ['env', 'env.example'],
    ];

    /** @var array<string, true> */
    protected array $allowedFixersWithoutDefaultCodeSample = [
        'Guanguans/dotenv_linter' => true,
    ];

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
            self::CONFIGURATION,
        ];
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     * @noinspection MissingParentCallInspection
     */
    protected function createFixer(): GenericsFixer
    {
        $fixer = new GenericsFixer;
        $fixer->configure(self::CONFIGURATION);

        return $fixer;
    }
}
