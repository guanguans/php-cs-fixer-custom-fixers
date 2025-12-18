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

use Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample;

/**
 * @see https://github.com/huacnlee/autocorrect
 */
final class AutocorrectFixer extends AbstractCommandLineToolFixer
{
    /**
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['txt', 'text', 'md', 'markdown'];
    }

    /**
     * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new FileSpecificCodeSample(
                $txt = <<<'TXT_WRAP'
                    Hello世界！

                    TXT_WRAP,
                $this
            ),
            new FileSpecificCodeSample($txt, $this, []),
        ];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['autocorrect'];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [
            '--fix' => true,
        ];
    }
}
