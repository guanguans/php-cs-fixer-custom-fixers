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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\Concerns;

trait LowestPriority
{
    /**
     * @see \PhpCsFixer\Fixer\Basic\NonPrintableCharacterFixer::getPriority()
     * @see \PhpCsFixer\Fixer\Whitespace\SingleBlankLineAtEofFixer::getPriority()
     */
    public function getPriority(): int
    {
        return -\PHP_INT_MAX;
    }
}
