<?php

/** @noinspection PhpInternalEntityUsedInspection */

declare(strict_types=1);

/**
 * Copyright (c) 2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\Concern;

use PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;

/**
 * @mixin \PhpCsFixer\Fixer\ConfigurableFixerTrait
 */
trait ConfigurationDefinitionOfExtensions
{
    public function firstExtension(): string
    {
        $extensions = $this->extensions();

        return $extensions[array_key_first($extensions)];
    }

    public function randomExtension(): string
    {
        $extensions = $this->extensions();

        return $extensions[array_rand($extensions)];
    }

    /**
     * @return list<string>
     */
    public function extensionPatterns(): array
    {
        return array_map(
            static fn (string $ext): string => \sprintf('/\.%s$/', str_replace('.', '\.', $ext)),
            $this->extensions()
        );
    }

    /**
     * @return non-empty-list<string>
     */
    public function extensions(): array
    {
        return $this->configuration[self::EXTENSIONS];
    }

    final protected function createConfigurationDefinition(): FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver(array_merge($this->defaultFixerOptions(), $this->fixerOptions()));
    }

    /**
     * @return list<\PhpCsFixer\FixerConfiguration\FixerOptionInterface>
     */
    protected function defaultFixerOptions(): array
    {
        return [
            (new FixerOptionBuilder(self::EXTENSIONS, 'The file extensions to format.'))
                ->setAllowedTypes(['string[]'])
                ->setDefault($this->defaultExtensions())
                ->getOption(),
        ];
    }

    /**
     * @return list<\PhpCsFixer\FixerConfiguration\FixerOptionInterface>
     */
    protected function fixerOptions(): array
    {
        return [];
    }

    /**
     * @return non-empty-list<string>
     */
    abstract protected function defaultExtensions(): array;
}
