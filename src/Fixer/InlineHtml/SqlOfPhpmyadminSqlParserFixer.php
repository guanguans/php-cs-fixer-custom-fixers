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
 * @see \PhpMyAdmin\SqlParser\Utils\Formatter::format()
 *
 * @property array{
 *     options: array<string, array<int, array<string, int|string>>|bool|string>,
 * } $configuration
 */
final class SqlOfPhpmyadminSqlParserFixer extends AbstractFixer
{
    public const CLAUSE_NEWLINE = 'clause_newline';
    public const INDENT_PARTS = 'indent_parts';
    public const INDENTATION = 'indentation';
    public const LINE_ENDING = 'line_ending';
    public const PARTS_NEWLINE = 'parts_newline';
    public const REMOVE_COMMENTS = 'remove_comments';

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function getAliasName(): string
    {
        return 'phpmyadmin/sql-parser';
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
     * @return list<\PhpCsFixer\FixerDefinition\CodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new CodeSample(
                $sql = <<<'SQL_WRAP'
                    SELECT customer_id, customer_name, COUNT(order_id) as total
                    FROM customers INNER JOIN orders ON customers.customer_id = orders.customer_id
                    GROUP BY customer_id, customer_name
                    HAVING COUNT(order_id) > 5
                    ORDER BY COUNT(order_id) DESC;
                    SQL_WRAP,
            ),
            new CodeSample($sql, [self::CLAUSE_NEWLINE => false]),
            new CodeSample($sql, [self::INDENTATION => '  ']),
        ];
    }

    protected function format(string $content): string
    {
        return Formatter::format($content, ['type' => 'text'] + $this->configuration);
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @see \PhpMyAdmin\SqlParser\Utils\Formatter::getDefaultOptions()
     *
     * @return list<\PhpCsFixer\FixerConfiguration\FixerOptionInterface>
     */
    private function fixerOptions(): array
    {
        return [
            (new FixerOptionBuilder(self::CLAUSE_NEWLINE, 'Whether each clause should be on a new line.'))
                ->setAllowedTypes(['bool'])
                ->setDefault(true)
                ->getOption(),
            (new FixerOptionBuilder(self::INDENT_PARTS, 'Whether each part of each clause should be indented.'))
                ->setAllowedTypes(['bool'])
                ->setDefault(true)
                ->getOption(),
            (new FixerOptionBuilder(self::INDENTATION, 'The string used for indentation.'))
                ->setAllowedTypes(['string', 'null'])
                ->setDefault(null)
                ->getOption(),
            (new FixerOptionBuilder(self::LINE_ENDING, 'The line ending used.'))
                ->setAllowedTypes(['string', 'null'])
                ->setDefault(null)
                ->getOption(),
            (new FixerOptionBuilder(self::PARTS_NEWLINE, 'Whether each part should be on a new line.'))
                ->setAllowedTypes(['bool'])
                ->setDefault(true)
                ->getOption(),
            (new FixerOptionBuilder(self::REMOVE_COMMENTS, 'Whether comments should be removed or not.'))
                ->setAllowedTypes(['bool'])
                ->setDefault(false)
                ->getOption(),
        ];
    }
}
