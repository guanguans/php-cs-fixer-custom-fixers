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

namespace Guanguans\PhpCsFixerCustomFixersTests\Feature\InlineHtml;

use Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractFixerTestCase;

/**
 * @internal
 *
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\InlineHtml\SqlOfDoctrineSqlFormatterFixer>
 */
final class SqlOfDoctrineSqlFormatterFixerTest extends AbstractFixerTestCase
{
    /**
     * @noinspection SqlResolve
     *
     * @return iterable<int|string, array{0: string, 1?: null|string, 2?: array<string, mixed>}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'default' => [
            <<<'SQL_WRAP'
                SELECT
                    customer_id,
                    customer_name,
                    COUNT(order_id) AS total
                FROM
                    customers
                    INNER JOIN orders ON customers.customer_id = orders.customer_id
                GROUP BY
                    customer_id,
                    customer_name
                HAVING
                    COUNT(order_id) > 5
                ORDER BY
                    COUNT(order_id) DESC;

                SQL_WRAP,
            <<<'SQL_WRAP'
                SELECT customer_id, customer_name, COUNT(order_id) AS total
                FROM customers INNER JOIN orders ON customers.customer_id = orders.customer_id
                GROUP BY customer_id, customer_name
                HAVING COUNT(order_id) > 5
                ORDER BY COUNT(order_id) DESC;
                SQL_WRAP,
        ];
    }
}
