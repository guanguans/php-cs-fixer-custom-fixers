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
use Illuminate\Support\Str;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

abstract class AbstractFixer extends AbstractInlineHtmlFixer
{
    /**
     * @see vendor/friendsofphp/php-cs-fixer/tests/Test/AbstractFixerTestCase.php:151
     *
     * @param \PhpCsFixer\Tokenizer\Tokens<\PhpCsFixer\Tokenizer\Token> $tokens
     */
    final protected function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        // $tokens[0] = new Token([\TOKEN_PARSE, $this->format($tokens[0]->getContent())]);
        // $tokens[0] = new Token([\T_INLINE_HTML, $this->format($tokens[0]->getContent())]);
        $code = $tokens->generateCode();
        $fixedCode = $this->format($code);

        if ($code !== $fixedCode) {
            // $tokens->setCode((string) Str::of($fixedCode)->finish($this->whitespacesConfig->getLineEnding()));
            $tokens->setCode((string) Str::of($fixedCode)->finish(\PHP_EOL));
        }
    }

    abstract protected function format(string $content): string;
}
