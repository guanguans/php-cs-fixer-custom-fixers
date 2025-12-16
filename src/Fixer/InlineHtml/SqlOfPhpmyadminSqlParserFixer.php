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
use PhpMyAdmin\SqlParser\Utils\Formatter;

/**
 * @see https://github.com/phpmyadmin/sql-parser
 *
 * @property array{
 *     options: array<string, array<int, array<string, int|string>>|bool|string>,
 * } $configuration
 */
final class SqlOfPhpmyadminSqlParserFixer extends AbstractFixer
{
    public const OPTIONS = 'options';

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function getAliasName(): string
    {
        return 'phpmyadmin/sql-parser';
    }

    /**
     * @return list<\PhpCsFixer\FixerConfiguration\FixerOptionInterface>
     */
    protected function fixerOptions(): array
    {
        return [
            /**  @see \PhpMyAdmin\SqlParser\Utils\Formatter::getDefaultOptions() */
            (new FixerOptionBuilder(self::OPTIONS, 'The formatting options.'))
                ->setAllowedTypes(['array'])
                ->setDefault(['type' => 'text'])
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
                [
                    self::OPTIONS => [
                        'type' => 'text',
                        // 'line_ending' => null,
                        'indentation' => '  ',
                        // 'clause_newline' => null,
                        // 'parts_newline' => null,
                    ],
                ]
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
        return Formatter::format($content, $this->configuration[self::OPTIONS]);
    }
}
