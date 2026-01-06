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

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Fixer\Phpdoc\ForceFQCNFixer;
use Ergebnis\PhpCsFixer\Config\RuleSet\Php85;
use ErickSkrauch\PhpCsFixer\Fixers as ErickSkrauchFixers;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixerCustomFixers\Fixers as PhpCsFixerCustomFixers;

return array_filter(
    array_merge(
        [new ForceFQCNFixer],
        iterator_to_array(new ErickSkrauchFixers),
        iterator_to_array(new PhpCsFixerCustomFixers)
    ),
    static fn (FixerInterface $fixer): bool => !$fixer instanceof DeprecatedFixerInterface
        && !\array_key_exists($fixer->getName(), Php85::create()->rules()->toArray())
        && !\in_array(
            $fixer->getName(),
            [
                'ErickSkrauch/align_multiline_parameters',
                'ErickSkrauch/blank_line_around_class_body',

                'PhpCsFixerCustomFixers/comment_surrounded_by_spaces',
                'PhpCsFixerCustomFixers/declare_after_opening_tag',
                'PhpCsFixerCustomFixers/isset_to_array_key_exists',
                'PhpCsFixerCustomFixers/no_commented_out_code',
                // 'PhpCsFixerCustomFixers/no_leading_slash_in_global_namespace',
                'PhpCsFixerCustomFixers/no_nullable_boolean_type',
                'PhpCsFixerCustomFixers/phpdoc_only_allowed_annotations',
                'PhpCsFixerCustomFixers/typed_class_constant', // @since 8.3
            ],
            true
        )
);
