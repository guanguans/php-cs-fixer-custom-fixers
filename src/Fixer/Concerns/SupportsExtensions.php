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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\Concerns;

use Illuminate\Support\Str;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;

/**
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractConfigurableFixer
 */
trait SupportsExtensions
{
    // /** @var string */
    // public const EXTENSIONS = 'extensions';

    public function supports(\SplFileInfo $file): bool
    {
        return Str::of($file->getExtension())->is($this->configuration[self::EXTENSIONS])
            || Str::of($file->getBasename())->lower()->endsWith($this->configuration[self::EXTENSIONS]);
    }

    protected function fixerOptionOfExtensions(): FixerOptionInterface
    {
        return (new FixerOptionBuilder(self::EXTENSIONS, 'The file extensions to format.'))
            ->setAllowedTypes(['array'])
            ->setDefault($this->defaultExtensions())
            ->getOption();
    }

    /**
     * @return list<string>
     */
    abstract protected function defaultExtensions(): array;
}
