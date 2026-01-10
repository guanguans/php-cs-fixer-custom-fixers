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

use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyCommandContract;
use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyNameContract;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\DependencyName;
use Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample;

/**
 * @see https://github.com/crate-ci/typos
 */
final class TyposFixer extends AbstractCommandLineToolFixer implements DependencyCommandContract, DependencyNameContract
{
    use DependencyName;

    // public function supports(\SplFileInfo $file): bool
    // {
    //     return true;
    // }

    /**
     * @codeCoverageIgnore
     */
    public function dependencyCommand(): string
    {
        switch (\PHP_OS_FAMILY) {
            case 'Darwin':
                return 'brew install typos-cli';
            case 'Windows':
            case 'Linux':
            default:
                // https://github.com/crate-ci/gh-install
                return 'cargo install typos-cli --locked';
        }
    }

    /**
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['txt', '*'];
    }

    /**
     * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new FileSpecificCodeSample(
                $txt = <<<'TXT_WRAP'
                    Symplify
                    TXT_WRAP,
                $this,
            ),
            new FileSpecificCodeSample($txt, $this, [self::OPTIONS => ['--sort' => true]]),
        ];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return [$this->getDependencyName()];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [
            '--write-changes' => true,
        ];
    }
}
