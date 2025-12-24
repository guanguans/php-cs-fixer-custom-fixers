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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\Concern;

use Guanguans\PhpCsFixerCustomFixers\Exception\InvalidFixerConfigurationException;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;

/**
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\Configurable
 *
 * @property array{
 *     extensions: list<string>,
 * } $configuration
 */
trait ConfigurableOfExtensions
{
    // /** @var string */
    // public const EXTENSIONS = 'extensions';

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
        $extensions = $this->configuration[self::EXTENSIONS];

        if ([] === $extensions) {
            throw new InvalidFixerConfigurationException(
                $this->getName(),
                'Invalid configuration for extensions, it must not be empty.',
            );
        }

        return $extensions;
    }

    /**
     * @return list<string>
     */
    abstract protected function defaultExtensions(): array;

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function fixerOptionOfExtensions(): FixerOptionInterface
    {
        return (new FixerOptionBuilder(self::EXTENSIONS, 'The supported file extensions are used for formatting.'))
            ->setAllowedTypes(['string[]'])
            ->setDefault($this->defaultExtensions())
            ->getOption();
    }
}
