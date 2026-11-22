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
 * @see https://github.com/keithamus/sort-package-json
 */
final class SortPackageJsonFixer extends AbstractCommandLineToolFixer implements DependencyCommandContract, DependencyNameContract
{
    use DependencyName;

    /**
     * @codeCoverageIgnore
     */
    public function dependencyCommand(): string
    {
        switch (\PHP_OS_FAMILY) {
            case 'Darwin':
            case 'Windows':
            case 'Linux':
            default:
                return 'npm install -g sort-package-json';
        }
    }

    /**
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return [/** @see SortListItemOfSameScalarTypeRector */ 'package.json'];
    }

    /**
     * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new FileSpecificCodeSample(
                $packageJson = <<<'PACKAGE_JSON_WRAP'
                    {
                        "$schema": "https://www.schemastore.org/package.json",
                        "type": "module",
                        "private": true,
                        "scripts": {
                            "build": "vite build",
                            "dev": "vite"
                        },
                        "devDependencies": {
                            "@tailwindcss/vite": "^4.0.0",
                            "laravel-vite-plugin": "^3.1",
                            "concurrently": "^10.0.3",
                            "tailwindcss": "^4.0.0",
                            "vite": "^8.0.0"
                        },
                        "optionalDependencies": {
                            "@laravel/multiplex": "^0.4.1"
                        }
                    }

                    PACKAGE_JSON_WRAP,
                $this,
            ),
            new FileSpecificCodeSample($packageJson, $this, [self::OPTIONS => ['--quiet' => true]]),
        ];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return [$this->getDependencyName()];
    }

    protected function requiredOptions(): array
    {
        return [];
    }
}
