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
use Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample;
use Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractFixerTestCase;
use Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractSpecificFixerTestCase;

/**
 * @internal
 *
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\GenericsFixer>
 */
final class GenericsFixerTest extends AbstractSpecificFixerTestCase
{
    public const CONFIGURATION = [
        AbstractCommandLineToolFixer::COMMAND => ['dotenv-linter', 'fix'],
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
        $fixer = new class('dotenv-linter') extends GenericsFixer {
            /**
             * @return list<string>
             */
            protected function defaultExtensions(): array
            {
                return array_merge(parent::defaultExtensions(), ['env', 'env.example']);
            }

            protected function codeSamples(): array
            {
                return array_merge(parent::codeSamples(), [
                    new FileSpecificCodeSample(
                        <<<'ENV_WRAP'
                            FOO= BAR
                            BAR = FOO

                            ENV_WRAP,
                        $this,
                        GenericsFixerTest::CONFIGURATION
                    ),
                    new FileSpecificCodeSample(
                        <<<'ENV_WRAP'
                            FOO=${BAR
                            BAR="$BAR}"

                            ENV_WRAP,
                        $this,
                        GenericsFixerTest::CONFIGURATION
                    ),
                ]);
            }
        };
        // $fixer = new GenericsFixer('dotenv-linter');
        $fixer->configure(self::CONFIGURATION);

        return $fixer;
    }
}
