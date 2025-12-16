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

/**
 * @see https://github.com/reteps/dockerfmt
 * @see https://github.com/hadolint/hadolint
 */
final class DockerfmtFixer extends AbstractFixer
{
    /**
     * @return non-empty-list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['Dockerfile'];
    }

    /**
     * @return list<\PhpCsFixer\FixerDefinition\CodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new CodeSample(
                <<<'DOCKERFILE_WRAP'
                    RUN	foo \
                        # comment 1
                    && \
                    # comment 2
                    bar && \
                    # comment 3
                    baz
                    DOCKERFILE_WRAP
            ),
        ];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['dockerfmt'];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [
            '--newline' => true,
            '--space-redirects' => true,
            '--write' => true,
        ];
    }
}
