<?php

/** @noinspection SensitiveParameterInspection */

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
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concerns\AllowRisky;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concerns\HighestPriority;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concerns\InlineHtmlCandidate;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concerns\SupportsExtensions;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

abstract class AbstractInlineHtmlFixer extends AbstractConfigurableFixer
{
    use AllowRisky;
    use HighestPriority;
    use InlineHtmlCandidate;
    use SupportsExtensions;

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            $summary = "Format a [{$this->getShortHeadlineName()}] file.",
            [new CodeSample($summary)]
        );
    }

    protected function createConfigurationDefinition(): FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver(array_merge([$this->fixerOptionOfExtensions()], $this->fixerOptions()));
    }

    /**
     * @noinspection PhpMemberCanBePulledUpInspection
     *
     * @return list<\PhpCsFixer\FixerConfiguration\FixerOptionInterface>
     */
    abstract protected function fixerOptions(): array;

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
