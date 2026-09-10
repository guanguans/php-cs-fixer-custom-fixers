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
use ErickSkrauch\PhpCsFixer\Fixer\FunctionNotation\AlignMultilineParametersFixer;
use ErickSkrauch\PhpCsFixer\Fixer\Whitespace\BlankLineAroundClassBodyFixer;
use ErickSkrauch\PhpCsFixer\Fixers as ErickSkrauchFixers;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixerCustomFixers\Fixer\CommentedOutFunctionFixer;
use PhpCsFixerCustomFixers\Fixer\CommentSurroundedBySpacesFixer;
use PhpCsFixerCustomFixers\Fixer\DeclareAfterOpeningTagFixer;
use PhpCsFixerCustomFixers\Fixer\FunctionParameterSeparationFixer;
use PhpCsFixerCustomFixers\Fixer\IssetToArrayKeyExistsFixer;
use PhpCsFixerCustomFixers\Fixer\NoCommentedOutCodeFixer;
use PhpCsFixerCustomFixers\Fixer\NoLeadingSlashInGlobalNamespaceFixer;
use PhpCsFixerCustomFixers\Fixer\NoNullableBooleanTypeFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocNoIncorrectVarAnnotationFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocOnlyAllowedAnnotationsFixer;
use PhpCsFixerCustomFixers\Fixer\TypedClassConstantFixer;
use PhpCsFixerCustomFixers\Fixers as PhpCsFixerCustomFixers;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withRules(array_map(
        '\get_class',
        array_filter(
            // [new ForceFQCNFixer, ...new ErickSkrauchFixers, ...new PhpCsFixerCustomFixers],
            array_merge(
                [new ForceFQCNFixer],
                iterator_to_array(new ErickSkrauchFixers),
                iterator_to_array(new PhpCsFixerCustomFixers)
            ),
            static fn (FixerInterface $fixer): bool => !$fixer instanceof DeprecatedFixerInterface && !\in_array(
                \get_class($fixer),
                [
                    AlignMultilineParametersFixer::class,
                    BlankLineAroundClassBodyFixer::class,

                    CommentedOutFunctionFixer::class,
                    CommentSurroundedBySpacesFixer::class,
                    DeclareAfterOpeningTagFixer::class,
                    FunctionParameterSeparationFixer::class,
                    IssetToArrayKeyExistsFixer::class,
                    NoCommentedOutCodeFixer::class,
                    // NoLeadingSlashInGlobalNamespaceFixer::class,
                    NoNullableBooleanTypeFixer::class,
                    // PhpdocNoIncorrectVarAnnotationFixer::class,
                    PhpdocOnlyAllowedAnnotationsFixer::class,
                    TypedClassConstantFixer::class, // @since 8.3
                ],
                true
            )
        )
    ));
