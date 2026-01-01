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

use Doctrine\SqlFormatter\NullHighlighter;
use Doctrine\SqlFormatter\SqlFormatter;
use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyNameContract;
use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer;
use Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;

/**
 * @see https://github.com/doctrine/sql-formatter
 *
 * @property array{
 *     indent_string: string,
 * } $configuration
 */
final class SqlOfDoctrineSqlFormatterFixer extends AbstractInlineHtmlFixer implements DependencyNameContract
{
    public const INDENT_STRING = 'indent_string';

    public function getDependencyName(): string
    {
        return 'doctrine/sql-formatter';
    }

    protected function fixCode(string $code): string
    {
        return $this->makeSqlFormatter()->format($code, $this->configuration[self::INDENT_STRING]);
    }

    /**
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['sql'];
    }

    /**
     * @noinspection SqlResolve
     *
     * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new FileSpecificCodeSample(
                $sql = <<<'SQL_WRAP'
                    SELECT customer_id, customer_name, COUNT(order_id) as total
                    FROM customers INNER JOIN orders ON customers.customer_id = orders.customer_id
                    GROUP BY customer_id, customer_name
                    HAVING COUNT(order_id) > 5
                    ORDER BY COUNT(order_id) DESC;

                    SQL_WRAP,
                $this,
            ),
            new FileSpecificCodeSample($sql, $this, [self::INDENT_STRING => '  ']),
        ];
    }

    private function makeSqlFormatter(): SqlFormatter
    {
        static $sqlFormatter;

        return $sqlFormatter ??= new SqlFormatter(new NullHighlighter);
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function fixerOptionOfIndentString(): FixerOptionInterface
    {
        return (new FixerOptionBuilder(
            self::INDENT_STRING,
            'The SQL string with HTML styles and formatting wrapped in a <pre> tag.'
        ))
            ->setAllowedTypes(['string'])
            ->setAllowedValues(self::ALLOWED_VALUES_OF_INDENT)
            ->setDefault('    ')
            ->getOption();
    }
}
