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
    /** @var string */
    public const OPTIONS = 'options';

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            "Format a [{$this->getShortHeadlineName()}] file.",
            [new CodeSample(
                <<<'SQL'
                    select
                        c.id, c.name, o.address,
                        o.orderedat
                    from
                        customers c
                    left join orders o on (o.customerid = c.id)
                    order by
                        o.orderedat;
                    SQL
            )],
            '',
            ''
        );
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
     * @return list<string>
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
