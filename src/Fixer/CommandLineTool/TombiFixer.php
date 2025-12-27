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
 * @see https://github.com/tombi-toml/tombi
 * @see https://github.com/tox-dev/toml-fmt
 */
final class TombiFixer extends AbstractCommandLineToolFixer implements DependencyCommandContract, DependencyNameContract
{
    use DependencyName;

    /**
     * @codeCoverageIgnore
     */
    public function dependencyCommand(): string
    {
        switch (\PHP_OS_FAMILY) {
            case 'Darwin':
                return 'brew install tombi';
            case 'Windows':
            case 'Linux':
            default:
                return 'npm install -g tombi';
        }
    }

    /**
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['toml'];
    }

    /**
     * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new FileSpecificCodeSample(
                <<<'TOML_WRAP'
                    key1 = "value1"

                    key2 = "value2"

                    TOML_WRAP,
                $this,
            ),
            new FileSpecificCodeSample(
                <<<'TOML_WRAP'
                    items = [
                      "a",
                      "b",
                      "c"
                    ]

                    TOML_WRAP,
                $this,
                [self::OPTIONS => ['--diff' => true]],
            ),
            new FileSpecificCodeSample(
                <<<'TOML_WRAP'
                    items = ["aaa", "bbb", "ccc", "ddd", "eee", "fff", "ggg", "hhh", "iii", "jjj", "kkk"]

                    TOML_WRAP,
                $this,
                [self::OPTIONS => ['--diff' => true]],
            ),
        ];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return [$this->getDependencyName(), 'format'];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [
            '--no-cache' => true,
            '--offline' => true,
            '--verbose' => true,
        ];
    }
}
