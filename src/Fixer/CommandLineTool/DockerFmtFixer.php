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
 * @see https://github.com/reteps/dockerfmt
 * @see https://github.com/hadolint/hadolint
 */
final class DockerFmtFixer extends AbstractCommandLineToolFixer
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            $summary = \sprintf('Format `%s` files using `dockerfmt`.', $this->defaultExtensions()[0]),
            [
                new CodeSample(
                    <<<'DOCKERFILE_WRAP'
                        RUN chmod +x /PrairieLearn/scripts/init.sh \
                        && mkdir /course{,{2..9}} \
                        && mkdir -p /jobs \
                        DOCKERFILE_WRAP
                ), new CodeSample(
                    <<<'DOCKERFILE_WRAP'
                        RUN chmod +x /PrairieLearn/scripts/init.sh \
                            && mkdir /course{,{2..9}} \
                            && mkdir -p /jobs \

                        DOCKERFILE_WRAP
                ),
            ],
            $summary,
            'Affected by `dockerfmt`'
        );
    }

    /**
     * @return non-empty-list<string>
     */
    public function defaultExtensions(): array
    {
        return ['Dockerfile'];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['dockerfmt'];
    }

    /**
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return ['--write', '--newline', '--space-redirects'];
    }
}
