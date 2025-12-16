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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\InlineHtml;

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\AllowRisky;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\CandidateOfInlineHtml;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\HighestPriority;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * @property array{
 *     extensions: list<string>,
 * } $configuration
 */
abstract class AbstractFixer extends AbstractInlineHtmlFixer
{
    use AllowRisky;
    use CandidateOfInlineHtml;
    use HighestPriority;

    /**
     * @param \PhpCsFixer\Tokenizer\Tokens<\PhpCsFixer\Tokenizer\Token> $tokens
     *
     * @throws \Throwable
     */
    final protected function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        $tokens[0] = new Token([\TOKEN_PARSE, $this->format($tokens[0]->getContent())]);
    }

    abstract protected function format(string $content): string;
}
