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

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * @see https://github.com/zhlint-project/zhlint
 */
final class ZhLintFixer extends AbstractCommandLineToolFixer
{
    public function supports(\SplFileInfo $file): bool
    {
        return parent::supports($file) || preg_match('/(zh|cn|chinese).*\.(md|markdown|text|txt)$/mi', $file->getBasename());
    }

    /**
     * @param \PhpCsFixer\Tokenizer\Tokens<\PhpCsFixer\Tokenizer\Token> $tokens
     */
    protected function finalFile(\SplFileInfo $file, Tokens $tokens): string
    {
        return (string) Str::of(parent::finalFile($file, $tokens))
            // ->chopStart($this->cmd())
            // ->chopStart(\DIRECTORY_SEPARATOR)
            // ->replaceStart($this->cmd(), '')
            // ->replaceStart(\DIRECTORY_SEPARATOR, '')
            ->whenStartsWith(
                $cmd = $this->cmd(),
                static fn (Stringable $file): Stringable => $file->replaceFirst($cmd, '')
            )
            ->whenStartsWith(
                \DIRECTORY_SEPARATOR,
                static fn (Stringable $file): Stringable => $file->replaceFirst(\DIRECTORY_SEPARATOR, '')
            );
    }

    protected function createTemporaryFile(
        ?string $directory = null,
        ?string $prefix = null,
        ?string $extension = null,
        bool $deferDelete = true
    ): string {
        return parent::createTemporaryFile($this->cmd(), $prefix, $extension, $deferDelete);
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['zhlint'];
    }

    /**
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return ['--fix'];
    }

    /**
     * @return list<\PhpCsFixer\FixerDefinition\CodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new CodeSample(
                <<<'MD_WRAP'
                    hello世界:
                    MD_WRAP
            ), new CodeSample(
                <<<'MD_WRAP'
                    hello 世界：
                    MD_WRAP
            ),
        ];
    }

    /**
     * @return non-empty-list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['zh_CN.md'];
    }

    private function cmd(): string
    {
        return $this->configuration[self::CWD] ?? getcwd();
    }
}
