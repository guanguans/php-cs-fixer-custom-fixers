<?php

/** @noinspection PhpDeprecationInspection */
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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\Utils;

/**
 * @implements \IteratorAggregate<FixerInterface>
 */
final class CommandLineToolFixer extends AbstractCommandLineToolFixer implements \IteratorAggregate
{
    private string $shortName;

    public function __construct(string $shortName)
    {
        parent::__construct();
        $this->shortName = Utils::camelCaseToUnderscore($shortName);
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function getShortName(): string
    {
        return $this->shortName;
    }

    /**
     * @return \Generator<FixerInterface>
     */
    public function getIterator(): \Traversable
    {
        yield $this;
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return [];
    }

    /**
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return [];
    }

    /**
     * @return list<\PhpCsFixer\FixerDefinition\CodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new CodeSample(
                <<<'ANY_WRAP'
                    any content
                    ANY_WRAP
            ), new CodeSample(
                <<<'ANY_WRAP'
                    any content
                    ANY_WRAP
            ),
        ];
    }

    /**
     * @return non-empty-list<string>
     */
    protected function defaultExtensions(): array
    {
        return [];
    }
}
