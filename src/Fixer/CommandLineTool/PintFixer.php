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

use Guanguans\PhpCsFixerCustomFixers\Fixer\Concerns\AlwaysCandidate;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concerns\LowestPriority;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concerns\SupportsExtensionsAndPathArg;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use function Guanguans\PhpCsFixerCustomFixers\Support\php_binary;

/**
 * @see https://github.com/prettier/plugin-php/blob/main/docs/recipes/php-cs-fixer/PrettierPHPFixer.php
 * @see https://github.com/laravel/pint
 * @see https://github.com/super-linter/super-linter
 */
final class PintFixer extends AbstractCommandLineToolFixer
{
    use AlwaysCandidate;
    use LowestPriority;
    use SupportsExtensionsAndPathArg;

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            $summary = \sprintf('Format `%s` files using `pint`.', $this->defaultExtensions()[0]),
            [
                new CodeSample(
                    <<<'PHP_WRAP'
                        <?php
                        if(!true){

                        }
                        PHP_WRAP
                ), new CodeSample(
                    <<<'PHP_WRAP'
                        <?php

                        if (! true) {

                        }

                        PHP_WRAP
                ),
            ],
            $summary,
            'Affected by `pint`'
        );
    }

    /**
     * @return non-empty-list<string>
     */
    public function defaultExtensions(): array
    {
        return ['php'];
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     */
    protected function configurePostNormalisation(): void
    {
        $this->configuration[self::ENV] += ['XDEBUG_MODE' => 'off'];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return [php_binary(), 'vendor/bin/pint'];
    }

    /**
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return [
            // '--config=pint.json',
            // '--format=json',
            '--no-interaction',
            // '--output-format=txt',
            // '--output-to-file=.build/pint/.pint.output',
            // '--parallel',
            // '--repair',
            // '--test',
        ];
    }
}
