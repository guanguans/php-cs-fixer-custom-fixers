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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool;

use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyNameContract;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\DependencyName;
use Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample;
use Illuminate\Support\Str;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;

/**
 * @implements \IteratorAggregate<\Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer>
 *
 * @property array{
 *     short_name: string,
 * } $configuration
 */
final class GenericsFixer extends AbstractCommandLineToolFixer implements \IteratorAggregate, DependencyNameContract
{
    use DependencyName;

    /** @api */
    public const SHORT_NAME = 'short_name';

    public function getShortName(): string
    {
        // return $this->configuration[self::SHORT_NAME];
        return \is_array($this->configuration) ? $this->configuration[self::SHORT_NAME] : parent::getShortName();
    }

    /**
     * @return \Generator<\Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer>
     */
    public function getIterator(): \Traversable
    {
        yield $this;
    }

    /**
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return [];
    }

    /**
     * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new FileSpecificCodeSample(
                <<<'ENV_WRAP'
                    FOO= BAR
                    BAR = FOO

                    ENV_WRAP,
                $this,
                [
                    /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DotenvLinterFixer */
                    self::COMMAND => ['dotenv-linter', 'fix'],
                    // self::OPTIONS => [],
                    self::EXTENSIONS => ['env', 'env.example'],
                ]
            ),
            new FileSpecificCodeSample(
                <<<'DOCKERFILE_WRAP'
                    RUN	foo \
                        # comment 1
                    && \
                    # comment 2
                    bar && \
                    # comment 3
                    baz

                    DOCKERFILE_WRAP,
                $this,
                [
                    /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DockerfmtFixer */
                    self::COMMAND => ['dockerfmt'],
                    self::OPTIONS => ['--newline' => true, '--space-redirects' => true, '--write' => true],
                    self::EXTENSIONS => ['Dockerfile'],
                ]
            ),
        ];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return [];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [];
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function fixerOptionOfWrapAttributesMinAttrs(): FixerOptionInterface
    {
        return (new FixerOptionBuilder(self::SHORT_NAME, 'The short name of the fixer.'))
            ->setAllowedTypes(['string', 'null'])
            ->setDefault(null)
            ->setNormalizer(
                /** @param \Symfony\Component\OptionsResolver\OptionsResolver $optionsResolver */
                static fn (/* OptionsResolver */ \ArrayAccess $optionsResolver, ?string $value): string => (string) Str::of(
                    $value ?? collect($optionsResolver[self::COMMAND])
                        ->filter(static fn (string $option): bool => is_file($option))
                        ->map(static fn (string $file): string => pathinfo($file, \PATHINFO_FILENAME))
                        ->implode('_')
                )
                    ->replace('-', '_')
                    ->replaceMatches('/_+/', '_')
                    ->snake()
                    ->trim('_')
            )
            ->getOption();
    }
}
