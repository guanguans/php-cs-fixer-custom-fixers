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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool;

use Illuminate\Support\Str;

/**
 * @implements \IteratorAggregate<\Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer>
 */
final class GenericsFixer extends AbstractCommandLineToolFixer implements \IteratorAggregate
{
    private string $shortName;

    public function __construct(string $shortName)
    {
        parent::__construct();
        // $this->shortName = Utils::camelCaseToUnderscore($shortName);
        $this->shortName = (string) Str::of($shortName)->snake();
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function getShortName(): string
    {
        return $this->shortName;
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
