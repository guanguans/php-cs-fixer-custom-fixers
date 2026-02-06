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

// putenv('PHP_CS_FIXER_ENFORCE_CACHE=1');
// putenv('PHP_CS_FIXER_IGNORE_ENV=1');
putenv('PHP_CS_FIXER_FUTURE_MODE=1');
putenv('PHP_CS_FIXER_NON_MONOLITHIC=1');
putenv('PHP_CS_FIXER_PARALLEL=1');

/**
 * @see https://mlocati.github.io/php-cs-fixer-configurator
 * @see https://github.com/laravel/pint/blob/main/app/Commands/DefaultCommand.php
 * @see https://github.com/laravel/pint/blob/main/app/Factories/ConfigurationFactory.php
 * @see https://github.com/laravel/pint/blob/main/app/Repositories/ConfigurationJsonRepository.php
 */
return [
    // '@auto' => true,
    // '@auto:risky' => true,
    '@autoPHPMigration' => true,
    '@autoPHPMigration:risky' => true,
    // '@autoPHPUnitMigration:risky' => true,
    // '@DoctrineAnnotation' => true,
    // '@PHP7x4Migration' => true,
    // '@PHP7x4Migration:risky' => true,
    // '@PHP8x0Migration' => true,
    // '@PHP8x0Migration:risky' => true,
    // '@PHP8x1Migration' => true,
    // '@PHP8x1Migration:risky' => true,
    // '@PHP8x2Migration' => true,
    // '@PHP8x2Migration:risky' => true,
    // '@PHP8x3Migration' => true,
    // '@PHP8x3Migration:risky' => true,
    // '@PHP8x4Migration' => true,
    // '@PHP8x4Migration:risky' => true,
    // '@PHP8x5Migration' => true,
    // '@PHP8x5Migration:risky' => true,
    // '@PhpCsFixer' => true,
    // '@PhpCsFixer:risky' => true,
    // '@PHPUnit8x4Migration:risky' => true,
    // '@PHPUnit9x1Migration:risky' => true,
    // '@PHPUnit10x0Migration:risky' => true,
    'align_multiline_comment' => [
        'comment_type' => 'phpdocs_only',
    ],
    'attribute_empty_parentheses' => [
        'use_parentheses' => false,
    ],
    'blank_line_before_statement' => [
        'statements' => [
            'break',
            // 'case',
            'continue',
            'declare',
            // 'default',
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
    ],
    'class_definition' => [
        'inline_constructor_arguments' => false,
        'multi_line_extends_each_single_line' => false,
        'single_item_single_line' => false,
        'single_line' => false,
        'space_before_parenthesis' => false,
    ],
    'comment_to_phpdoc' => [
        'ignored_tags' => [
            'codeCoverageIgnore',
            'codeCoverageIgnoreEnd',
            'codeCoverageIgnoreStart',
        ],
    ],
    'concat_space' => [
        'spacing' => 'none',
    ],
    'empty_loop_condition' => [
        'style' => 'for',
    ],
    'explicit_string_variable' => false,
    'final_class' => false,
    // 'final_internal_class' => false,
    // 'final_public_method_for_abstract_class' => false,
    'fully_qualified_strict_types' => [
        'import_symbols' => false,
        'leading_backslash_in_global_namespace' => false,
        'phpdoc_tags' => [
            // 'param',
            // 'phpstan-param',
            // 'phpstan-property',
            // 'phpstan-property-read',
            // 'phpstan-property-write',
            // 'phpstan-return',
            // 'phpstan-var',
            // 'property',
            // 'property-read',
            // 'property-write',
            // 'psalm-param',
            // 'psalm-property',
            // 'psalm-property-read',
            // 'psalm-property-write',
            // 'psalm-return',
            // 'psalm-var',
            // 'return',
            // 'see',
            // 'throws',
            // 'var',
        ],
    ],
    'logical_operators' => false,
    'mb_str_functions' => false,
    'native_function_invocation' => [
        'exclude' => [],
        'include' => ['@compiler_optimized', 'is_scalar'],
        'scope' => 'all',
        'strict' => true,
    ],
    'new_with_parentheses' => [
        'anonymous_class' => false,
        'named_class' => false,
    ],
    'no_extra_blank_lines' => [
        'tokens' => [
            'attribute',
            'break',
            'case',
            // 'comma',
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
    ],
    'ordered_traits' => [
        'case_sensitive' => true,
    ],
    'php_unit_data_provider_name' => [
        'prefix' => 'provide',
        'suffix' => 'Cases',
    ],
    'phpdoc_align' => [
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
    ],
    'phpdoc_line_span' => [
        'const' => 'single',
        'method' => 'multi',
        'property' => 'single',
    ],
    'phpdoc_no_alias_tag' => [
        'replacements' => [
            'link' => 'see',
            // 'property-read' => 'property',
            // 'property-write' => 'property',
            'type' => 'var',
        ],
    ],
    'phpdoc_order' => [
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
    ],
    'phpdoc_order_by_value' => [
        'annotations' => [
            'author',
            'covers',
            'coversNothing',
            'dataProvider',
            'depends',
            'group',
            'internal',
            // 'method',
            'mixin',
            'property',
            'property-read',
            'property-write',
            'requires',
            'throws',
            'uses',
        ],
    ],
    'phpdoc_to_param_type' => [
        'scalar_types' => true,
        'types_map' => [],
        'union_types' => true,
    ],
    'phpdoc_to_property_type' => [
        'scalar_types' => true,
        'types_map' => [],
        'union_types' => true,
    ],
    'phpdoc_to_return_type' => [
        'scalar_types' => true,
        'types_map' => [],
        'union_types' => true,
    ],
    'simplified_if_return' => true,
    'simplified_null_return' => true,
    'single_line_empty_body' => true,
    'statement_indentation' => [
        'stick_comment_to_next_continuous_control_statement' => true,
    ],
    'static_lambda' => false, // pest
    'static_private_method' => false,
];
