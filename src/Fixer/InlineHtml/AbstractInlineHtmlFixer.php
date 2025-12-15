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

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractConfigurableFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\AllowRisky;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\Definition;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\HighestPriority;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\InlineHtmlCandidate;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\SupportsExtensions;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * @property array{
 *     extensions: list<string>,
 * } $configuration
 */
abstract class AbstractInlineHtmlFixer extends AbstractConfigurableFixer
{
    use AllowRisky;
    use Definition;
    use HighestPriority;
    use InlineHtmlCandidate;
    use SupportsExtensions;

    /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\SupportsExtensions */
    public const EXTENSIONS = 'extensions';

    /**
     * @param \PhpCsFixer\Tokenizer\Tokens<\PhpCsFixer\Tokenizer\Token> $tokens
     *
     * @throws \Throwable
     */
    final protected function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        $tokens[0] = new Token([\TOKEN_PARSE, $this->format($tokens[0]->getContent())]);
    }

    /**
     * @return list<\PhpCsFixer\FixerConfiguration\FixerOptionInterface>
     */
    abstract protected function fixerOptions(): array;

    protected function createConfigurationDefinition(): FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver(array_merge([$this->fixerOptionOfExtensions()], $this->fixerOptions()));
    }

    abstract protected function format(string $content): string;
}
