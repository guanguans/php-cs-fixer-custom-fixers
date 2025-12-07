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

use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;

/**
 * @see https://gitlab.gnome.org/GNOME/libxml2/-/wikis/home
 * @see https://gnome.pages.gitlab.gnome.org/libxml2/xmllint.html
 */
final class XmlLintFixer extends AbstractCommandLineToolFixer
{
    /** @var string */
    public const WRAP_ATTRS_MIN_NUM = 'wrap_attrs_min_num';

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            "Format a [{$this->getShortHeadlineName()}] file.",
            [new CodeSample(
                <<<'xml_warp'
                    <?xml version="1.0" encoding="UTF-8"?>
                    <phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd" bootstrap="vendor/autoload.php" cacheDirectory=".build/phpunit/" colors="true">
                    </phpunit>
                    xml_warp
            )],
            '',
            ''
        );
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     *
     * @return list<\PhpCsFixer\FixerConfiguration\FixerOptionInterface>
     */
    protected function fixerOptions(): array
    {
        return [
            (new FixerOptionBuilder(
                self::WRAP_ATTRS_MIN_NUM,
                'Wrap attributes to multiple lines when the number of attributes is greater than or equal to this value.',
            ))
                ->setAllowedTypes(['int'])
                ->setDefault(5)
                ->getOption(),
        ];
    }

    /**
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['xml', 'xml.dist'];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['xmllint'];
    }

    /**
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return [
            // '--noblanks',
            // '--nocompact',
            '--format',
            '--pretty' => 1,
            '--output' => $this->finalFile,
            '--encode' => 'UTF-8',
        ];
    }

    protected function fixedCode(): string
    {
        return $this->formatAttributes(parent::fixedCode(), $this->configuration[self::WRAP_ATTRS_MIN_NUM]);
    }

    /**
     * @noinspection PhpSameParameterValueInspection
     */
    private function formatAttributes(string $xml, int $wrapAttrsMinNum = 5, int $indent = 2): string
    {
        return preg_replace_callback(
            '/<([^\s>\/]+)(\s+[^>]+?)(\s*\/?)>/',
            static function (array $matches) use ($wrapAttrsMinNum, $xml, $indent): string {
                [$fullTag, $tagName, $attrs, $selfClose] = $matches;

                // 属性数量小于阈值保持单行
                if (preg_match_all('/\s+[^\s=]+="/', $attrs) < $wrapAttrsMinNum) {
                    return $fullTag;
                }

                // 计算当前行的缩进
                $currentPos = strpos($xml, $fullTag);
                $lineStart = strrpos((string) substr($xml, 0, $currentPos), \PHP_EOL);
                $currentIndent = '';

                if (false !== $lineStart) {
                    $lineText = (string) substr($xml, $lineStart + 1, $currentPos - $lineStart - 1);
                    $currentIndent = str_repeat(' ', \strlen($lineText) - \strlen(ltrim($lineText)));
                }

                // 格式化属性为多行
                $attrIndent = str_repeat(' ', $indent);
                $multilineAttrs = preg_replace('/\s+([^\s=]+="[^"]*")/', "\n$currentIndent$attrIndent$1", $attrs);
                $tagClose = $selfClose ? "\n$currentIndent$selfClose>" : "\n$currentIndent>";

                return "<$tagName$multilineAttrs$tagClose";
            },
            $xml
        );
    }
}
