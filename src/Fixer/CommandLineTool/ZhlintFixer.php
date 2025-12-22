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

use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyCommandContract;
use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyNameContract;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\DependencyName;
use Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * @see https://github.com/zhlint-project/zhlint
 */
final class ZhlintFixer extends AbstractCommandLineToolFixer implements DependencyCommandContract, DependencyNameContract
{
    use DependencyName;

    public function supports(\SplFileInfo $file): bool
    {
        return parent::supports($file) || preg_match('/(zh|cn|chinese).*\.(md|markdown|text|txt)$/mi', $file->getBasename());
    }

    /**
     * @codeCoverageIgnore
     */
    public function dependencyCommand(): string
    {
        switch (\PHP_OS_FAMILY) {
            case 'Darwin':
            case 'Windows':
            case 'Linux':
            default:
                return 'npm install -g zhlint';
        }
    }

    /**
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['zh_CN.md'];
    }

    /**
     * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new FileSpecificCodeSample(
                $md = <<<'MD_WRAP'
                    3 minute(s) left 中文

                    case-abbr：Pure JavaScript (a.k.a. Vanilla) 中文

                    case-backslash：a \# b 中文\# __中文__ \# 中文 __\#__ __中文__\#中文__\#__

                    case-traditional：a「b『c』d」e 中文

                    mark-raw：a `b` c `d`e`f` g`h`i 中文

                    mark-type：a__[b](x)__c__[ d ](y)__e 中文

                    space-brackets：(x)a(b)c (d )e( f) g ( h ) i（j）k （l） m __( a )__ b( __c__ )d(e) 中文

                    space-punctuation：中文 。 中文(中文)中文。中文 . 中文（中文）中文.

                    space-quotations: a " hello world " b 中文

                    unify-punctuation：中文,中文 （中文） 中文'中文'中文"中文"中文 （中文）（中文）中文 （中文）。

                    MD_WRAP,
                $this,
            ),
            new FileSpecificCodeSample($md, $this, []),
        ];
    }

    /**
     * @param \PhpCsFixer\Tokenizer\Tokens<\PhpCsFixer\Tokenizer\Token> $tokens
     */
    protected function finalFileFor(\SplFileInfo $file, Tokens $tokens): string
    {
        return (string) Str::of(parent::finalFileFor($file, $tokens))
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
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [
            '--fix' => true,
        ];
    }

    private function cmd(): string
    {
        return $this->configuration[self::CWD] ?? getcwd();
    }
}
