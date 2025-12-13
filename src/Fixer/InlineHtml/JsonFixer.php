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

use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerDefinition\CodeSample;

/**
 * @see https://www.php.net/manual/en/function.json-encode.php
 * @see https://www.php.net/manual/en/function.json-decode.php
 * @see https://github.com/ergebnis/composer-normalize
 * @see https://github.com/Seldaek/jsonlint
 * @see https://github.com/TheDragonCode/codestyler/blob/5.x/app/Fixers/JsonFixer.php
 *
 * @property array{
 *     decode_flags: int,
 *     encode_flags: int,
 *     indent_string: string,
 * } $configuration
 */
final class JsonFixer extends AbstractInlineHtmlFixer
{
    public const DECODE_FLAGS = 'decode_flags';
    public const ENCODE_FLAGS = 'encode_flags';
    public const INDENT_STRING = 'indent_string';

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function getAliasName(): string
    {
        return 'json_encode()/json_decode()';
    }

    /**
     * @return list<\PhpCsFixer\FixerConfiguration\FixerOptionInterface>
     */
    protected function fixerOptions(): array
    {
        return [
            (new FixerOptionBuilder(self::DECODE_FLAGS, 'The flags to use when decoding JSON.'))
                ->setAllowedTypes(['int'])
                ->setDefault(0)
                ->getOption(),
            (new FixerOptionBuilder(self::ENCODE_FLAGS, 'The flags to use when encoding JSON.'))
                ->setAllowedTypes(['int'])
                ->setDefault(\JSON_PRETTY_PRINT | \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES | \JSON_THROW_ON_ERROR)
                ->getOption(),
            (new FixerOptionBuilder(self::INDENT_STRING, 'The string to use for indentation.'))
                ->setAllowedTypes(['string'])
                ->setDefault('    ')
                ->getOption(),
        ];
    }

    /**
     * @throws \JsonException
     */
    protected function format(string $content): string
    {
        return $this->formatIndentation(
            json_encode(
                json_decode(
                    $content,
                    true,
                    512,
                    \JSON_THROW_ON_ERROR | $this->configuration[self::DECODE_FLAGS]
                ),
                \JSON_THROW_ON_ERROR | $this->configuration[self::ENCODE_FLAGS]
            ),
            $this->configuration[self::INDENT_STRING]
        );
    }

    /**
     * @return list<\PhpCsFixer\FixerDefinition\CodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new CodeSample(
                <<<'JSON'
                    {
                    "foo": "bar",
                        "baz": {
                    "qux": "quux"
                        }
                    }
                    JSON
            ),
            new CodeSample(
                <<<'JSON'
                    {
                        "phrase": "\u4f60\u597d\uff01"
                    }
                    JSON,
            ),
        ];
    }

    /**
     * @return non-empty-list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['json'];
    }

    /**
     * @see https://github.com/dingo/api/blob/master/src/Http/Response/Format/JsonOptionalFormatting.php
     */
    private function formatIndentation(string $json, string $indentString = '    '): string
    {
        return preg_replace('/(^|\G) {4}/m', "$indentString\$1", $json);
    }
}
