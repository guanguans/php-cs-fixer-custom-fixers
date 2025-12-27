<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\InlineHtml;

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer;
use Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;

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
     * @throws \JsonException
     */
    protected function fixCode(string $code): string
    {
        return $this->formatIndentation(
            json_encode(
                json_decode(
                    $code,
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
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['json'];
    }

    /**
     * @see \Rector\Php73\Rector\String_\SensitiveHereNowDocRector
     *
     * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new FileSpecificCodeSample(
                <<<'JSON_WRAP'
                    {
                        "phrase": "\u4f60\u597d\uff01"
                    }

                    JSON_WRAP,
                $this
            ),
            new FileSpecificCodeSample(
                <<<'JSON_WRAP'
                    {
                        "name": "guanguans/php-cs-fixer-custom-fixers",
                        "keywords": [
                            "dev",
                            "fixer",
                            "standards"
                        ],
                        "authors": [
                            {
                                "name": "guanguans",
                                "email": "ityaozm@gmail.com",
                                "homepage": "https://github.com/guanguans"
                            }
                        ]
                    }

                    JSON_WRAP,
                $this,
                [self::INDENT_STRING => '  ']
            ),
        ];
    }

    /**
     * @see https://github.com/dingo/api/blob/master/src/Http/Response/Format/JsonOptionalFormatting.php
     */
    private function formatIndentation(string $json, string $indentString = '    '): string
    {
        return preg_replace('/(^|\G) {4}/m', "$indentString\$1", $json);
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @see \Composer\IO\BaseIO::log()
     * @see https://github.com/Seldaek/monolog/blob/main/src/Monolog/Utils.php#L16
     *
     * @return list<\PhpCsFixer\FixerConfiguration\FixerOptionInterface>
     */
    private function fixerOptions(): array
    {
        return [
            (new FixerOptionBuilder(self::DECODE_FLAGS, 'The flags to use when decoding JSON.'))
                ->setAllowedTypes(['int'])
                ->setDefault(0)
                ->getOption(),
            (new FixerOptionBuilder(self::ENCODE_FLAGS, 'The flags to use when encoding JSON.'))
                ->setAllowedTypes(['int'])
                ->setDefault(
                    \JSON_INVALID_UTF8_IGNORE |
                    \JSON_INVALID_UTF8_SUBSTITUTE |
                    \JSON_PARTIAL_OUTPUT_ON_ERROR |
                    \JSON_PRESERVE_ZERO_FRACTION |
                    \JSON_PRETTY_PRINT |
                    \JSON_THROW_ON_ERROR |
                    \JSON_UNESCAPED_SLASHES |
                    \JSON_UNESCAPED_UNICODE
                )
                ->getOption(),
            (new FixerOptionBuilder(self::INDENT_STRING, 'The string to use for indentation.'))
                ->setAllowedTypes(['string'])
                ->setAllowedValues(self::ALLOWED_VALUES_OF_INDENT)
                ->setDefault('    ')
                ->getOption(),
        ];
    }
}
