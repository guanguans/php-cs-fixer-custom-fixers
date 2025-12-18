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

use Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample;

/**
 * @see https://github.com/sqlfluff/sqlfluff
 */
final class SqlfluffFixer extends AbstractCommandLineToolFixer
{
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
            new FileSpecificCodeSample($sql, $this, []),
        ];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['sqlfluff', 'format'];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [
            '--dialect' => 'mysql',
        ];
    }
}
