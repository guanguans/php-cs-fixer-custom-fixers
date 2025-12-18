<?php

/** @noinspection PhpInternalEntityUsedInspection */
/** @noinspection PhpMemberCanBePulledUpInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */

declare(strict_types=1);

/**
 * Copyright (c) 2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

namespace Guanguans\PhpCsFixerCustomFixersTests\Feature\InlineHtml;

use Guanguans\PhpCsFixerCustomFixers\Fixer\InlineHtml\JsonFixer;
use Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractFixerTestCase;
use PhpCsFixer\ConfigurationException\InvalidFixerConfigurationException;
use PhpCsFixer\FixerConfiguration\FixerOption;

/**
 * @internal
 *
 * @covers \Guanguans\PhpCsFixerCustomFixers\Fixer\InlineHtml\JsonFixer
 *
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\InlineHtml\JsonFixer>
 */
final class JsonFixerTest extends AbstractFixerTestCase
{
    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidFixerConfigurationException::class);
        $this->expectExceptionMessage(
            \sprintf(
                '[%s] Invalid configuration: The option "invalid" does not exist. Defined options are: %s.',
                $this->fixer->getName(),
                collect($this->fixer->getConfigurationDefinition()->getOptions())
                    ->map(fn (FixerOption $option): string => "\"{$option->getName()}\"")
                    ->implode(', ')
            )
        );

        $this->fixer->configure(['invalid' => true]);
    }

    public function testConfigure(): void
    {
        $this->fixer->configure($configuration = ['indent_string' => ' ']);

        self::assertSame(
            $configuration,
            array_intersect_key(
                // (fn (JsonFixer $fixer): array => $fixer->configuration)->call($this->fixer, $this->fixer),
                \Closure::bind(
                    static fn (JsonFixer $fixer): array => $fixer->configuration,
                    null,
                    JsonFixer::class
                )($this->fixer),
                $configuration
            )
        );
    }

    /**
     * @dataProvider provideFixCases
     *
     * @param array<string, mixed> $configuration
     */
    public function testFix(string $expected, ?string $input = null, array $configuration = []): void
    {
        $this->fixer->configure($configuration);

        $this->doTest($expected, $input, $this->fixer->makeDummySplFileInfo());
    }

    /**
     * @return iterable<int|string, array{0: string, 1?: null|string, 2?: array<string, mixed>}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'json unescaped unicode' => [
            <<<'JSON'
                {
                    "phrase": "你好！"
                }

                JSON,
            <<<'JSON'
                {
                    "phrase": "\u4f60\u597d\uff01"
                }
                JSON,
        ];
    }
}
