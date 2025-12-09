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
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpMyAdmin\SqlParser\Utils\Formatter;

/**
 * @see https://github.com/doctrine/sql-formatter
 * @see https://github.com/phpmyadmin/sql-parser
 */
final class PhpMyAdminSqlFixer extends AbstractInlineHtmlFixer
{
    public const OPTIONS = 'options';

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            $summary = \sprintf('Format `%s` files using `doctrine/sql-formatter`.', $this->defaultExtensions()[0]),
            [
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
            ],
            $summary,
            'Affected by `doctrine/sql-formatter`'
        );
    }

    /**
     * @return non-empty-list<string>
     */
    public function defaultExtensions(): array
    {
        return ['sql'];
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
}
