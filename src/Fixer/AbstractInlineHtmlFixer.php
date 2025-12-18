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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer;

use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\AllowRisky;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\CandidateOfInlineHtml;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\ConfigurableOfExtensions;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\DefinitionOfExtensions;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\HighestPriority;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\SupportsOfExtensions;
use Illuminate\Support\Str;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\Tokenizer\Tokens;

abstract class AbstractInlineHtmlFixer extends AbstractConfigurableFixer /* implements WhitespacesAwareFixerInterface */
{
    use AllowRisky;
    use CandidateOfInlineHtml;
    use ConfigurableOfExtensions;
    use DefinitionOfExtensions;
    use HighestPriority;
    use SupportsOfExtensions;

    /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\ConfigurableOfExtensions */
    public const EXTENSIONS = 'extensions';

    /**
     * @see vendor/friendsofphp/php-cs-fixer/tests/Test/AbstractFixerTestCase.php:151
     *
     * @param \PhpCsFixer\Tokenizer\Tokens<\PhpCsFixer\Tokenizer\Token> $tokens
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        // $tokens[0] = new Token([\T_INLINE_HTML, $this->fixCode($tokens[0]->getContent())]);
        $code = $tokens->generateCode();
        $fixedCode = $this->fixCode($code);

        if ($code !== $fixedCode) {
            // $tokens->setCode((string) Str::of($fixedCode)->finish($this->whitespacesConfig->getLineEnding()));
            $tokens->setCode((string) Str::of($fixedCode)->finish(\PHP_EOL));
        }
    }

    abstract protected function fixCode(string $code): string;
}
