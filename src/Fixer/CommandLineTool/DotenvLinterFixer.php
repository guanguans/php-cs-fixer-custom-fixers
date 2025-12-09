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

use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;

/**
 * @see https://github.com/dotenv-linter/dotenv-linter
 */
final class DotenvLinterFixer extends AbstractCommandLineToolFixer
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            $summary = \sprintf('Format `%s` files using `dotenv-linter`.', $this->defaultExtensions()[0]),
            [
                new CodeSample(
                    <<<'ENV_WRAP'
                        APP_DEBUG=true
                        APP_KEY=
                        APP_ENV=local


                        DB_URL=http://localhost


                        ENV_WRAP
                ), new CodeSample(
                    <<<'ENV_WRAP'
                        APP_DEBUG=true
                        APP_ENV=local
                        APP_KEY=

                        DB_URL=http://localhost

                        ENV_WRAP
                ),
            ],
            $summary,
            'Affected by `dotenv-linter`'
        );
    }

    /**
     * @return non-empty-list<string>
     */
    public function defaultExtensions(): array
    {
        return ['env', 'env.example'];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['dotenv-linter', 'fix'];
    }

    /**
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return [];
    }
}
