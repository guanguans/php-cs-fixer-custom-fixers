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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\Concern;

trait LowestPriority
{
    /**
     * The lower the value, the later the fixer will be applied.
     *
     * @see \PhpCsFixer\Fixer\Basic\NonPrintableCharacterFixer::getPriority()
     * @see \PhpCsFixer\Fixer\Whitespace\SingleBlankLineAtEofFixer::getPriority()
     */
    public function getPriority(): int
    {
        return -\PHP_INT_MAX;
    }
}
