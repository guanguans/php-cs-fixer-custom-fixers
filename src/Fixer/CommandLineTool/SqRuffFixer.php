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

use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;

/**
 * @see https://github.com/quarylabs/sqruff
 */
final class SqRuffFixer extends AbstractCommandLineToolFixer
{
    /**
     * @noinspection SqlResolve
     */
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            $summary = \sprintf('Format `%s` files using `sqruff`.', $this->firstExtension()),
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
                        select
                            c.id, c.name, o.address,
                            o.orderedat
                        from
                            customers c
                        left join orders o on (o.customerid = c.id)
                        order by
                            o.orderedat;

                        SQL_WRAP
                ),
            ],
            $summary,
            'Affected by `sqruff`'
        );
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['sqruff', 'fix'];
    }

    /**
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return [
            // '--dialect' => 'mysql',
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
