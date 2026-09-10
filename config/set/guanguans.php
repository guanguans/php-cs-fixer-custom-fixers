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

use PhpCsFixer\Fixer\Alias\MbStrFunctionsFixer;
use PhpCsFixer\Fixer\AttributeNotation\AttributeEmptyParenthesesFixer;
use PhpCsFixer\Fixer\Basic\SingleLineEmptyBodyFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassDefinitionFixer;
use PhpCsFixer\Fixer\ClassNotation\FinalClassFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedTraitsFixer;
use PhpCsFixer\Fixer\ClassNotation\StaticPrivateMethodFixer;
use PhpCsFixer\Fixer\Comment\CommentToPhpdocFixer;
use PhpCsFixer\Fixer\ConstantNotation\NativeConstantInvocationFixer;
use PhpCsFixer\Fixer\ControlStructure\EmptyLoopConditionFixer;
use PhpCsFixer\Fixer\ControlStructure\SimplifiedIfReturnFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\FunctionNotation\PhpdocToParamTypeFixer;
use PhpCsFixer\Fixer\FunctionNotation\PhpdocToPropertyTypeFixer;
use PhpCsFixer\Fixer\FunctionNotation\PhpdocToReturnTypeFixer;
use PhpCsFixer\Fixer\FunctionNotation\StaticLambdaFixer;
use PhpCsFixer\Fixer\Import\FullyQualifiedStrictTypesFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\LogicalOperatorsFixer;
use PhpCsFixer\Fixer\Operator\NewWithParenthesesFixer;
use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use PhpCsFixer\Fixer\Operator\OperatorLinebreakFixer;
use PhpCsFixer\Fixer\Phpdoc\AlignMultilineCommentFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocAlignFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoAliasTagFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocOrderByValueFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocOrderFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocToCommentFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDataProviderNameFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitInternalClassFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestClassRequiresCoversFixer;
use PhpCsFixer\Fixer\ReturnNotation\SimplifiedNullReturnFixer;
use PhpCsFixer\Fixer\Semicolon\MultilineWhitespaceBeforeSemicolonsFixer;
use PhpCsFixer\Fixer\StringNotation\ExplicitStringVariableFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer;
use PhpCsFixer\Fixer\Whitespace\NoExtraBlankLinesFixer;
use PhpCsFixer\Fixer\Whitespace\StatementIndentationFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayListItemNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayOpenerAndCloserNewlineFixer;
use Symplify\CodingStandard\Fixer\Spacing\MethodChainingNewlineFixer;
use Symplify\CodingStandard\Fixer\Spacing\SpaceAfterCommaHereNowDocFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return ECSConfig::configure()
    ->withCache(\sprintf('%s/.build/ecs/%s/', getcwd(), pathinfo(__FILE__, \PATHINFO_FILENAME)))
    ->withParallel()
    ->withSkip([
        ExplicitStringVariableFixer::class,
        FinalClassFixer::class,
        LogicalOperatorsFixer::class,
        MbStrFunctionsFixer::class,
        NotOperatorWithSuccessorSpaceFixer::class,
        OperatorLinebreakFixer::class,
        PhpdocToCommentFixer::class,
        PhpUnitInternalClassFixer::class,
        PhpUnitTestClassRequiresCoversFixer::class,
        StaticPrivateMethodFixer::class,

        ArrayListItemNewlineFixer::class,
        ArrayOpenerAndCloserNewlineFixer::class,
        MethodChainingNewlineFixer::class,
        SpaceAfterCommaHereNowDocFixer::class,

        StaticLambdaFixer::class => [
            getcwd().'/tests/*Test.php',
            getcwd().'/tests/Pest.php',
        ],
    ])
    // ->withPreparedSets(
    //     common: true,
    //     laravel: true,
    //     standaloneLine: true,
    // )
    ->withSets([
        Guanguans\PhpCsFixerCustomFixers\Set\SetList::DYNAMIC,
        Guanguans\PhpCsFixerCustomFixers\Set\SetList::CUSTOM,

        // SetList::PSR_12,
        // SetList::PER_CS,

        SetList::COMMON,
        // SetList::ARRAY,
        // SetList::CASING,
        // SetList::CLEAN_CODE,
        // SetList::CLEANUP,
        // SetList::COMMENTS,
        // SetList::CONTROL_STRUCTURES,
        // SetList::DOCBLOCK,
        // SetList::NAMESPACES,
        // SetList::SPACES,

        // SetList::DOCTRINE_ANNOTATIONS,
        // SetList::LARAVEL,
        SetList::STANDALONE_LINE,
    ])
    ->withRules([
        NoUnusedImportsFixer::class,
        SimplifiedIfReturnFixer::class,
        SimplifiedNullReturnFixer::class,
        SingleLineEmptyBodyFixer::class,
    ])
    ->withConfiguredRule(ClassAttributesSeparationFixer::class, [
        'elements' => [
            'const' => 'only_if_meta',
            'method' => 'one',
            'property' => 'only_if_meta',
            'trait_import' => 'none',
        ],
    ])
    ->withConfiguredRule(NativeConstantInvocationFixer::class, [
        'exclude' => [
            'false',
            'null',
            'true',
        ],
        'fix_built_in' => true,
        'include' => [],
        'scope' => 'all',
        'strict' => false,
    ])
    ->withConfiguredRule(YodaStyleFixer::class, [
        'always_move_variable' => true,
        'equal' => true,
        'identical' => true,
        'less_and_greater' => true,
    ])
    ->withConfiguredRule(MultilineWhitespaceBeforeSemicolonsFixer::class, [
        'strategy' => 'no_multi_line',
    ])
    ->withConfiguredRule(AlignMultilineCommentFixer::class, [
        'comment_type' => 'phpdocs_only',
    ])
    ->withConfiguredRule(AttributeEmptyParenthesesFixer::class, [
        'use_parentheses' => false,
    ])
    ->withConfiguredRule(BlankLineBeforeStatementFixer::class, [
        'statements' => [
            'break',
            'continue',
            'declare',
            'do',
            'exit',
            'for',
            'foreach',
            'goto',
            'if',
            'include',
            'include_once',
            'phpdoc',
            'require',
            'require_once',
            'return',
            'switch',
            'throw',
            'try',
            'while',
            'yield',
            'yield_from',
        ],
    ])
    ->withConfiguredRule(ClassDefinitionFixer::class, [
        'inline_constructor_arguments' => false,
        'multi_line_extends_each_single_line' => false,
        'single_item_single_line' => false,
        'single_line' => false,
        'space_before_parenthesis' => false,
    ])
    ->withConfiguredRule(CommentToPhpdocFixer::class, [
        'ignored_tags' => [
            'codeCoverageIgnore',
            'codeCoverageIgnoreEnd',
            'codeCoverageIgnoreStart',
        ],
    ])
    ->withConfiguredRule(ConcatSpaceFixer::class, [
        'spacing' => 'none',
    ])
    ->withConfiguredRule(EmptyLoopConditionFixer::class, [
        'style' => 'for',
    ])
    ->withConfiguredRule(FullyQualifiedStrictTypesFixer::class, [
        'import_symbols' => false,
        'leading_backslash_in_global_namespace' => false,
        'phpdoc_tags' => [],
    ])
    ->withConfiguredRule(NativeFunctionInvocationFixer::class, [
        'exclude' => [],
        'include' => [
            '@compiler_optimized',
            'is_scalar',
        ],
        'scope' => 'all',
        'strict' => true,
    ])
    ->withConfiguredRule(NewWithParenthesesFixer::class, [
        'anonymous_class' => false,
        'named_class' => false,
    ])
    ->withConfiguredRule(NoExtraBlankLinesFixer::class, [
        'tokens' => [
            'attribute',
            'break',
            'case',
            'continue',
            'curly_brace_block',
            'default',
            'extra',
            'parenthesis_brace_block',
            'return',
            'square_brace_block',
            'switch',
            'throw',
            'use',
        ],
    ])
    ->withConfiguredRule(OrderedTraitsFixer::class, [
        'case_sensitive' => true,
    ])
    ->withConfiguredRule(PhpUnitDataProviderNameFixer::class, [
        'prefix' => 'provide',
        'suffix' => 'Cases',
    ])
    ->withConfiguredRule(PhpdocAlignFixer::class, [
        'align' => 'left',
        'spacing' => 1,
        'tags' => [
            'method',
            'param',
            'property',
            'property-read',
            'property-write',
            'return',
            'see',
            'throws',
            'type',
            'var',
        ],
    ])
    ->withConfiguredRule(PhpdocLineSpanFixer::class, [
        'const' => 'single',
        'method' => 'multi',
        'property' => 'single',
    ])
    ->withConfiguredRule(PhpdocNoAliasTagFixer::class, [
        'replacements' => [
            'link' => 'see',
            'type' => 'var',
        ],
    ])
    ->withConfiguredRule(PhpdocOrderFixer::class, [
        'order' => [
            'see',
            'template',
            'template-covariant',
            'template-extends',
            'template-implements',
            'extends',
            'implements',
            'mixin',
            'api',
            'api-extendable',
            'deprecated',
            'final',
            'internal',
            'readonly',
            'covers',
            'uses',
            'dataProvider',
            'param',
            'throws',
            'return',
            'codeCoverageIgnore',
            'noinspection',
            'phan-suppress',
            'phpcsSuppress',
            'phpstan-ignore',
            'psalm-suppress',
        ],
    ])
    ->withConfiguredRule(PhpdocOrderByValueFixer::class, [
        'annotations' => [
            'author',
            'covers',
            'coversNothing',
            'dataProvider',
            'depends',
            'group',
            'internal',
            'mixin',
            'property',
            'property-read',
            'property-write',
            'requires',
            'throws',
            'uses',
        ],
    ])
    ->withConfiguredRule(PhpdocToParamTypeFixer::class, [
        'scalar_types' => true,
        'types_map' => [],
        'union_types' => true,
    ])
    ->withConfiguredRule(PhpdocToPropertyTypeFixer::class, [
        'scalar_types' => true,
        'types_map' => [],
        'union_types' => true,
    ])
    ->withConfiguredRule(PhpdocToReturnTypeFixer::class, [
        'scalar_types' => true,
        'types_map' => [],
        'union_types' => true,
    ])
    ->withConfiguredRule(StatementIndentationFixer::class, [
        'stick_comment_to_next_continuous_control_statement' => true,
    ]);
