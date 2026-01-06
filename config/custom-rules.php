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

use PhpCsFixer\Fixer\FixerInterface;

return array_reduce(
    require __DIR__.'/custom-fixers.php',
    static function (array $carry, FixerInterface $fixer): array {
        $carry[$fixer->getName()] = true;

        return $carry;
    },
    []
);
