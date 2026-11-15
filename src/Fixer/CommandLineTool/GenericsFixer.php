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
use Illuminate\Support\Str;

/**
 * @implements \IteratorAggregate<\Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer>
 */
final class GenericsFixer extends AbstractCommandLineToolFixer implements \IteratorAggregate, DependencyNameContract
{
    use DependencyName;

    public function getShortName(): string
    {
        return \is_array($this->configuration)
            ? (string) Str::of(
                $this->configuration['name'] ?? collect($this->configuration[self::COMMAND])
                    ->filter(static fn (string $file): bool => is_file($file))
                    ->map(static fn (string $file): string => pathinfo($file, \PATHINFO_BASENAME))
                    ->implode('_')
            )
                ->replace('-', '_')
                ->replaceMatches('/_+/', '_')
                ->snake()
                ->trim('_')
            : parent::getShortName();
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
        return [];
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
}
