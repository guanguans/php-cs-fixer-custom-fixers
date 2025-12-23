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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern;

use PhpCsFixer\Tokenizer\Tokens;

trait HasTokens
{
    protected Tokens $tokens;

    public function getTokens(): Tokens
    {
        return $this->tokens;
    }

    /**
     * @param \PhpCsFixer\Tokenizer\Tokens<\PhpCsFixer\Tokenizer\Token> $tokens
     */
    protected function setTokens(Tokens $tokens): void
    {
        $this->tokens = $tokens;
    }
}
