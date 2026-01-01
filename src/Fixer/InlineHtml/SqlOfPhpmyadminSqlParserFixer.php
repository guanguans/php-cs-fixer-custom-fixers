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

use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyNameContract;
use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer;
use Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpMyAdmin\SqlParser\Utils\Formatter;

/**
 * @see https://github.com/phpmyadmin/sql-parser
 * @see \PhpMyAdmin\SqlParser\Utils\Formatter::format()
 *
 * @property array{
 *     clause_newline: bool,
 *     indent_parts: bool,
 *     indentation: ?string,
 *     line_ending: ?string,
 *     parts_newline: bool,
 *     remove_comments: bool,
 * } $configuration
 */
final class SqlOfPhpmyadminSqlParserFixer extends AbstractInlineHtmlFixer implements DependencyNameContract
{
    public const CLAUSE_NEWLINE = 'clause_newline';
    public const INDENT_PARTS = 'indent_parts';
    public const INDENTATION = 'indentation';
    public const LINE_ENDING = 'line_ending';
    public const PARTS_NEWLINE = 'parts_newline';
    public const REMOVE_COMMENTS = 'remove_comments';

    public function getDependencyName(): string
    {
        return 'phpmyadmin/sql-parser';
    }

    protected function fixCode(string $code): string
    {
        return Formatter::format($code, ['type' => 'text'] + $this->configuration);
    }

    /**
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['sql'];
    }

    /**
     * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
     *
     * @noinspection SqlResolve
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
            new FileSpecificCodeSample($sql, $this, [self::CLAUSE_NEWLINE => false]),
            new FileSpecificCodeSample($sql, $this, [self::INDENTATION => '  ']),
        ];
    }

    /**
     * @see \PhpMyAdmin\SqlParser\Utils\Formatter::getDefaultOptions()
     *
     * @return list<\PhpCsFixer\FixerConfiguration\FixerOptionInterface>
     *
     * @noinspection PhpUnusedPrivateMethodInspection
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
                ->setAllowedValues(array_merge(self::ALLOWED_VALUES_OF_INDENT, [null]))
                ->setDefault(null)
                ->getOption(),
            (new FixerOptionBuilder(self::LINE_ENDING, 'The line ending used.'))
                ->setAllowedTypes(['string', 'null'])
                ->setAllowedValues(array_merge(self::ALLOWED_VALUES_OF_LINE_ENDING, [null]))
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
