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

namespace Guanguans\PhpCsFixerCustomFixersTests\Feature\CommandLineTool;

use Guanguans\PhpCsFixerCustomFixersTests\Feature\AbstractFixerTestCase;

/**
 * @extends AbstractFixerTestCase<\Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\XmllintFixer>
 *
 * @internal
 */
final class XmllintFixerTest extends AbstractFixerTestCase
{
    /**
     * @return iterable<int|string, array{0: string, 1?: null|string, 2?: array<string, mixed>}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'default' => [
            /** @lang TEXT */
            <<<'XML_WRAP'
                <?xml version="1.0" encoding="UTF-8"?>
                <phpunit
                  bootstrap="vendor/autoload.php"
                  colors="true"
                  failOnDeprecation="true"
                  failOnRisky="true"
                  failOnWarning="true"
                >
                  <php>
                    <ini name="memory_limit" value="-1"/>
                    <env name="DUMP_LIGHT_ARRAY" value=""/>
                  </php>
                  <source>
                    <include>
                      <directory>src/</directory>
                    </include>
                  </source>
                </phpunit>

                XML_WRAP,
            <<<'XML_WRAP'
                <phpunit bootstrap="vendor/autoload.php" colors="true" failOnDeprecation="true" failOnRisky="true" failOnWarning="true">
                  <php>
                    <ini name="memory_limit" value="-1"   />
                    <env name="DUMP_LIGHT_ARRAY" value=""></env>
                  </php>
                  <source>
                      <include>
                          <directory>src/</directory>
                      </include>
                  </source>
                </phpunit>
                XML_WRAP,
        ];
    }
}
