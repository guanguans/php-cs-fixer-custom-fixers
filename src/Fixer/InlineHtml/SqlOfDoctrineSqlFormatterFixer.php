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

use Doctrine\SqlFormatter\NullHighlighter;
use Doctrine\SqlFormatter\SqlFormatter;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerDefinition\CodeSample;

/**
 * @see https://github.com/doctrine/sql-formatter
 *
 * @property array{
 *     indent_string: string,
 * } $configuration
 */
final class SqlOfDoctrineSqlFormatterFixer extends AbstractFixer
{
    public const INDENT_STRING = 'indent_string';

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function getAliasName(): string
    {
        return 'doctrine/sql-formatter';
    }

    /**
     * @return list<\PhpCsFixer\FixerConfiguration\FixerOptionInterface>
     */
    protected function fixerOptions(): array
    {
        return [
            (new FixerOptionBuilder(
                self::INDENT_STRING,
                'The SQL string with HTML styles and formatting wrapped in a <pre> tag.'
            ))
                ->setAllowedTypes(['string'])
                ->setDefault('    ')
                ->getOption(),
        ];
    }

    /**
     * @noinspection SqlResolve
     *
     * @return list<\PhpCsFixer\FixerDefinition\CodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new CodeSample(
                <<<'SQL_WRAP'
                    SELECT customer_id, customer_name, COUNT(order_id) as total
                    FROM customers INNER JOIN orders ON customers.customer_id = orders.customer_id
                    GROUP BY customer_id, customer_name
                    HAVING COUNT(order_id) > 5
                    ORDER BY COUNT(order_id) DESC;
                    SQL_WRAP
            ),
            new CodeSample(
                <<<'SQL_WRAP'
                    SELECT customer_id, customer_name, COUNT(order_id) as total
                    FROM customers INNER JOIN orders ON customers.customer_id = orders.customer_id
                    GROUP BY customer_id, customer_name
                    HAVING COUNT(order_id) > 5
                    ORDER BY COUNT(order_id) DESC;
                    SQL_WRAP,
                [self::INDENT_STRING => '  ']
            ),
        ];
    }

    /**
     * @return non-empty-list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['sql'];
    }

    protected function format(string $content): string
    {
        return $this->createSqlFormatter()->format($content, $this->configuration[self::INDENT_STRING]);
    }

    private function createSqlFormatter(): SqlFormatter
    {
        static $sqlFormatter;

        return $sqlFormatter ??= new SqlFormatter(new NullHighlighter);
    }
}
