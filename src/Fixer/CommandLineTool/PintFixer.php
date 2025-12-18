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

use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\CandidateOfAny;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\LowestPriority;
use PhpCsFixer\FixerDefinition\VersionSpecification;
use PhpCsFixer\FixerDefinition\VersionSpecificCodeSample;
use function Guanguans\PhpCsFixerCustomFixers\Support\php_binary;

/**
 * @see https://github.com/laravel/pint
 * @see https://github.com/prettier/plugin-php/blob/main/docs/recipes/php-cs-fixer/PrettierPHPFixer.php
 * @see https://github.com/super-linter/super-linter
 */
final class PintFixer extends AbstractFixer
{
    use CandidateOfAny;
    use LowestPriority;

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
    protected function defaultExtensions(): array
    {
        return ['php'];
    }

    /**
     * @see \PhpCsFixer\FixerDefinition
     *
     * @return list<\PhpCsFixer\FixerDefinition\VersionSpecificCodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new VersionSpecificCodeSample(
                $php = <<<'PHP_WRAP'
                    <?php

                    if (!$isFormatted) {

                    }

                    PHP_WRAP,
                new VersionSpecification(80200),
            ),
            new VersionSpecificCodeSample($php, new VersionSpecification(80200), []),
        ];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return [php_binary(), 'vendor/bin/pint'];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [
            '--no-interaction' => true,
            // '--parallel' => true,
            // '--repair' => true,
        ];
    }
}
