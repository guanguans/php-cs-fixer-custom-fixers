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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\Concern;

use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyNameContract;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;

/**
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\ConcreteName
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\SupportsOfExtensions
 */
trait DefinitionOfExtensions
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            $this->summary(),
            $this->codeSamples(),
            $this->description(),
            $this->riskyDescription()
        );
    }

    protected function summary(): string
    {
        return $this instanceof DependencyNameContract
            ? "Format `{$this->firstExtension()}` files using `{$this->getDependencyName()}`."
            : "Format `{$this->firstExtension()}` files.";
    }

    /**
     * @return list<\PhpCsFixer\FixerDefinition\CodeSampleInterface>
     */
    abstract protected function codeSamples(): array;

    protected function description(): string
    {
        return $this->summary();
    }

    protected function riskyDescription(): string
    {
        return $this instanceof DependencyNameContract
            ? "It depends on the configuration of `{$this->getDependencyName()}`."
            : 'It depends on the configuration.';
    }
}
