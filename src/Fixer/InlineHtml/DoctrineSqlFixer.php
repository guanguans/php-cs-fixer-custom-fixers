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

/**
 * @see https://github.com/doctrine/sql-formatter
 * @see https://github.com/phpmyadmin/sql-parser
 */
final class DoctrineSqlFixer extends AbstractInlineHtmlFixer
{
    public const INDENT_STRING = 'indent_string';

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

    protected function format(string $content): string
    {
        return $this->createSqlFormatter()->format($content, $this->configuration[self::INDENT_STRING]);
    }

    /**
     * @noinspection SqlResolve
     *
     * @return non-empty-list<array{code: string, configuration: null|array}>
     */
    protected function codeSamples(): array
    {
        return [
            [
                'code' => <<<'SQL_WRAP'
                    SELECT
                        c.id,
                        c.name,
                        o.address,
                        o.orderedat
                    FROM
                        customers c
                        LEFT JOIN orders o ON (o.customerid = c.id)
                    ORDER BY
                        o.orderedat;
                    SQL_WRAP,
            ], [
                'code' => <<<'SQL_WRAP'
                    SELECT
                        c.id,
                        c.name,
                        o.address,
                        o.orderedat
                    FROM
                        customers c
                        LEFT JOIN orders o ON (o.customerid = c.id)
                    ORDER BY
                        o.orderedat;
                    SQL_WRAP,
            ],
        ];
    }

    /**
     * @return non-empty-list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['sql'];
    }

    private function createSqlFormatter(): SqlFormatter
    {
        static $sqlFormatter;

        return $sqlFormatter ??= new SqlFormatter(new NullHighlighter);
    }
}
