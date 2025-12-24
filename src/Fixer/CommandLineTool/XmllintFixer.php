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
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;

/**
 * @see https://gnome.pages.gitlab.gnome.org/libxml2/xmllint.html
 * @see https://gitlab.gnome.org/GNOME/libxml2/-/wikis/home
 *
 * @property array{
 *     wrap_attributes_min_attrs: int,
 * } $configuration
 */
final class XmllintFixer extends AbstractCommandLineToolFixer implements DependencyCommandContract, DependencyNameContract
{
    use DependencyName;

    /** @see blade-formatter --help */
    public const WRAP_ATTRIBUTES_MIN_ATTRS = 'wrap_attributes_min_attrs';

    /**
     * @codeCoverageIgnore
     */
    public function dependencyCommand(): string
    {
        switch (\PHP_OS_FAMILY) {
            case 'Darwin':
                return 'brew install libxml2';
            case 'Windows':
                return 'choco install libxml2';
            case 'Linux':
            default:
                return 'sudo apt-get update && sudo apt-get install -y libxml2-utils';
        }
    }

    protected function fixCode(string $code): string
    {
        return $this->formatAttributes(parent::fixCode($code), $this->configuration[self::WRAP_ATTRIBUTES_MIN_ATTRS]);
    }

    /**
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['xml', 'xml.dist'];
    }

    /**
     * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new FileSpecificCodeSample(
                $xml = <<<'XML_WRAP'
                    <phpunit bootstrap="vendor/autoload.php" colors="true" failOnDeprecation="true" failOnRisky="true" failOnWarning="true">
                      <php>
                        <ini name="memory_limit" value="-1"   />
                        <env name="DUMP_LIGHT_ARRAY" value=""></env>
                      </php>
                      <source>
                          <include>
                              <directory>src/</directory>
                          </include>
                      </source>
                    </phpunit>

                    XML_WRAP,
                $this,
            ),
            new FileSpecificCodeSample($xml, $this, []),
        ];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['xmllint'];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [
            '--encode' => 'UTF-8',
            '--format' => true,
            // '--noblanks' => true,
            // '--nocompact' => true,
            '--output' => $this->filePath,
            '--pretty' => 1,
        ];
    }

    private function formatAttributes(string $xml, int $wrapAttributesMinAttrs = 5, int $indent = 2): string
    {
        return preg_replace_callback(
            '/<([^\s>\/]+)(\s+[^>]+?)(\s*\/?)>/',
            static function (array $matches) use ($wrapAttributesMinAttrs, $xml, $indent): string {
                [$fullTag, $tagName, $attrs, $selfClose] = $matches;

                // 属性数量小于阈值保持单行
                if (preg_match_all('/\s+[^\s=]+="/', $attrs) < $wrapAttributesMinAttrs) {
                    return $fullTag;
                }

                // 计算当前行的缩进
                $currentPos = (int) strpos($xml, $fullTag);
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

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @see blade-formatter --help
     */
    private function fixerOptionOfWrapAttributesMinAttrs(): FixerOptionInterface
    {
        return (new FixerOptionBuilder(
            self::WRAP_ATTRIBUTES_MIN_ATTRS,
            'Minimum number of xml tag attributes for force wrap attribute options.',
        ))
            ->setAllowedTypes(['int'])
            ->setDefault(5)
            ->getOption();
    }
}
