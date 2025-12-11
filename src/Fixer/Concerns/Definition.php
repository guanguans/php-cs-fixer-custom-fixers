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

use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;

/**
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\Concerns\SupportsExtensions
 */
trait Definition
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
        return "Format `{$this->firstExtension()}` files using `{$this->getAliasName()}`.";
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
        return "It depends on the configuration of `{$this->getAliasName()}`.";
    }
}
