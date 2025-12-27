<?php

/** @noinspection PhpInternalEntityUsedInspection */

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

namespace Guanguans\PhpCsFixerCustomFixersTests\Feature;

/**
 * @property \Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractConfigurableFixer|\Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer|\Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer $fixer
 */
abstract class AbstractFixerTestCase extends \PhpCsFixer\Tests\Test\AbstractFixerTestCase
{
    use SetUp;
}
