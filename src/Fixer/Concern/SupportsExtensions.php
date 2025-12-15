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

use Illuminate\Support\Str;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;

/**
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractConfigurableFixer
 *
 * @property array{
 *     extensions: list<string>,
 * } $configuration
 */
trait SupportsExtensions
{
    // /** @var string */
    // public const EXTENSIONS = 'extensions';

    public function supports(\SplFileInfo $file): bool
    {
        $lowerExtensions = array_map(static fn (string $ext): string => strtolower($ext), $this->extensions());

        return Str::of($file->getExtension())->lower()->is($lowerExtensions)
            || Str::of($file->getBasename())->lower()->endsWith($lowerExtensions);
    }

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

    /**
     * @return non-empty-list<string>
     */
    abstract protected function defaultExtensions(): array;

    protected function fixerOptionOfExtensions(): FixerOptionInterface
    {
        return (new FixerOptionBuilder(self::EXTENSIONS, 'The file extensions to format.'))
            ->setAllowedTypes(['string[]'])
            ->setDefault($this->defaultExtensions())
            ->getOption();
    }
}
