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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern;

use Guanguans\PhpCsFixerCustomFixers\Exception\InvalidFixerConfigurationException;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;

/**
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\Configurable
 *
 * @property array{
 *     options: array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>,
 * } $configuration
 */
trait ConfigurableOfOptions
{
    // /** @var string */
    // public const OPTIONS = 'options';

    /**
     * @see \Symfony\Component\Console\Input\ArrayInput::__toString()
     * @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\MarkdownlintFixer::requiredOptions()
     *
     * @return list<null|scalar>
     */
    protected function normalizedOptions(): array
    {
        return array_merge(...array_map(
            fn ($value, string $key): array => $this->normalizeOption($key, $value),
            $options = $this->options(),
            array_keys($options)
        ));
    }

    /**
     * @return array<string, mixed>
     */
    abstract protected function requiredOptions(): array;

    /**
     * @return array<string, mixed>
     */
    private function options(): array
    {
        return $this->configuration[self::OPTIONS] + $this->requiredOptions();
    }

    /**
     * @param mixed $value
     *
     * @return list<null|scalar>
     */
    private function normalizeOption(string $key, $value): array
    {
        if (!str_starts_with($key, '-')) {
            throw new InvalidFixerConfigurationException(
                $this->getName(),
                "Invalid configuration for options item key [$key], it must start with [-] or [--].",
            );
        }

        $value = $this->normalizeOptionValue($value);

        if (null === $value || false === $value) {
            return [];
        }

        if (true === $value) {
            return [$key];
        }

        if (\is_array($value)) {
            // return array_merge(...array_map(static fn ($v): array => [$key, $v], $value));
            return array_merge(...array_map(fn ($v): array => $this->normalizeOption($key, $v), $value));
        }

        return [$key, $value];
    }

    /**
     * @see \Guanguans\PhpCsFixerCustomFixers\Support\Utils::lazyConfig()
     * @see \PhpCsFixer\Cache\Signature::makeJsonEncodable()
     *
     * @param mixed $value
     *
     * @return null|list<null|scalar>|scalar
     */
    private function normalizeOptionValue($value)
    {
        if (null === $value || \is_scalar($value)) {
            return $value;
        }

        if ($value instanceof \JsonSerializable && \is_callable($value)) {
            return $this->normalizeOptionValue($value($this));
        }

        if (\is_array($value)) {
            return array_map(fn ($v) => $this->normalizeOptionValue($v), $value);
        }

        throw new InvalidFixerConfigurationException(
            $this->getName(),
            // \sprintf('Invalid configuration for options item type [%s].', get_debug_type($value))
            \sprintf('Invalid configuration for options item type [%s].', \gettype($value))
        );
    }

    /**
     * @see \Symfony\Component\Process\Process::__construct()
     *
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function fixerOptionOfOptions(): FixerOptionInterface
    {
        /** @see \PhpCsFixer\FixerConfiguration\FixerOption::unbind() */
        // $name = $this->getName();

        return (new FixerOptionBuilder(self::OPTIONS, 'The command options to run listed as separate entries.'))
            ->setAllowedTypes(['array'])
            /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\XmllintFixer::requiredOptions() */
            // ->setDefault($this->requiredOptions())
            ->setDefault([])
            ->getOption();
    }
}
