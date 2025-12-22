<?php

/** @noinspection MissingParentCallInspection */
/** @noinspection PhpInternalEntityUsedInspection */
/** @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

/**
 * Copyright (c) 2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

namespace Guanguans\PhpCsFixerCustomFixersTests\Feature\Concern;

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixers;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use PhpCsFixer\ConfigurationException\InvalidFixerConfigurationException;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\FixerConfiguration\FixerOption;

/**
 * @mixin \Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractFixerTestCase
 * @mixin \Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractSpecificFixerTestCase
 */
trait SetUp
{
    final public function testInvalidConfiguration(): void
    {
        if (!$this->fixer instanceof ConfigurableFixerInterface) {
            self::markTestSkipped(\sprintf(
                'The fixer [%s] is not configurable.',
                $this->fixer->getShortClassName(),
            ));
        }

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

    /**
     * @noinspection PhpUndefinedNamespaceInspection
     * @noinspection PhpUndefinedClassInspection
     * @noinspection PhpFullyQualifiedNameUsageInspection
     * @noinspection PhpLanguageLevelInspection
     *
     * @dataProvider provideFixCases
     *
     * @param array<string, mixed> $configuration
     */
    // ️⃣[\PHPUnit\Framework\Attributes\DataProvider('provideFixCases')]
    #[\PHPUnit\Framework\Attributes\DataProvider('provideFixCases')]
    final public function testFix(string $expected, ?string $input = null, array $configuration = []): void
    {
        if ($this->fixer instanceof ConfigurableFixerInterface) {
            $this->fixer->configure($configuration);
        }

        $this->doTest($expected, $input, $this->fixer->makeDummySplFileInfo());
    }

    /**
     * @return iterable<int|string, array{0: string, 1?: null|string, 2?: array<string, mixed>}>
     */
    abstract public static function provideFixCases(): iterable;

    protected function createFixer(): AbstractFixer
    {
        static $fixers;

        $name = (string) Str::of((new \ReflectionClass(static::class))->getShortName())->whenEndsWith(
            $suffix = 'Test',
            static fn (Stringable $file): Stringable => $file->replaceLast($suffix, '')
        );

        return ($fixers ??= collect(Fixers::make()))->firstOrFail(
            fn (AbstractFixer $fixer): bool => $fixer->getShortClassName() === $name
        );
    }
}
