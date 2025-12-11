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
final class PhpMyAdminSqlFixer extends AbstractInlineHtmlFixer
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

    protected function format(string $content): string
    {
        return Formatter::format($content, $this->configuration[self::OPTIONS]);
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
                    select
                        c.id, c.name, o.address,
                        o.orderedat
                    from
                        customers c
                    left join orders o on (o.customerid = c.id)
                    order by
                        o.orderedat;
                    SQL_WRAP
            ), new CodeSample(
                <<<'SQL_WRAP'
                    SELECT
                        c.id,
                        c.name,
                        o.address,
                        o.orderedat
                    FROM
                        customers c
                    LEFT JOIN orders o ON
                        (o.customerid = c.id)
                    ORDER BY
                        o.orderedat;
                    SQL_WRAP
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
}
