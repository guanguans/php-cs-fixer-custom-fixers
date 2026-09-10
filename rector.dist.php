<?php

/** @noinspection PhpInternalEntityUsedInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

use Ergebnis\Rector\Rules\Expressions\Arrays\SortAssociativeArrayByKeyRector;
use Ergebnis\Rector\Rules\Faker\GeneratorPropertyFetchToMethodCallRector;
use Ergebnis\Rector\Rules\Files\ReferenceNamespacedSymbolsRelativeToNamespacePrefixRector;
use Guanguans\PhpCsFixerCustomFixers\Support\Rector\UpdateCodeSamplesRector;
use Guanguans\RectorRules\Rector\ClassMethod\PrivateToProtectedVisibilityForTraitRector;
use Guanguans\RectorRules\Rector\File\AddNoinspectionDocblockToFileFirstStmtRector;
use Guanguans\RectorRules\Rector\FunctionLike\RenameGarbageParamNameRector;
use Guanguans\RectorRules\Rector\Name\RenameToConventionalCaseNameRector;
use PhpParser\NodeVisitor\ParentConnectingVisitor;
use Rector\CodeQuality\Rector\LogicalAnd\LogicalToBooleanRector;
use Rector\CodingStyle\Rector\Assign\SplitDoubleAssignRector;
use Rector\CodingStyle\Rector\ClassLike\NewlineBetweenClassLikeStmtsRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\DowngradePhp74\Rector\Array_\DowngradeArraySpreadRector;
use Rector\DowngradePhp80\Rector\FuncCall\DowngradeStrContainsRector;
use Rector\DowngradePhp80\Rector\FuncCall\DowngradeStrEndsWithRector;
use Rector\DowngradePhp80\Rector\FuncCall\DowngradeStrStartsWithRector;
use Rector\DowngradePhp81\Rector\FuncCall\DowngradeArrayIsListRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitThisCallRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Transform\Rector\String_\StringToClassConstantRector;
use Rector\ValueObject\PhpVersion;

error_reporting(\E_ALL & ~\E_DEPRECATED & ~\E_USER_DEPRECATED);

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/config/',
        __DIR__.'/src/',
        __DIR__.'/tests/',
        __DIR__.'/composer-bump',
    ])
    ->withRootFiles()
    ->withSkip(['*/Fixtures/*', __DIR__.'/tests.php'])
    ->withCache(__DIR__.'/.build/rector/')
    // ->withoutParallel()
    ->withParallel()
    // ->withImportNames(importDocBlockNames: false, importShortClasses: false, removeUnusedImports: false)
    ->withImportNames(true, false, false, false)
    // ->withEditorUrl()
    ->withFluentCallNewLine()
    ->withTreatClassesAsFinal()
    ->withTypeGuardedClasses([])
    // ->withAttributesSets(phpunit: true, all: true)
    // ->withComposerBased(phpunit: true/* , laravel: true */)
    ->withComposerBased(false, false, true)
    ->withPhpVersion(PhpVersion::PHP_74)
    ->withPhpLevel(74)
    // ->withDowngradeSets(php74: true)
    // ->withPhpSets(php74: true)
    // ->withPreparedSets(
    //     deadCode: true,
    //     codeQuality: true,
    //     codingStyle: true,
    //     typeDeclarations: true,
    //     typeDeclarationDocblocks: true,
    //     privatization: true,
    //     naming: true,
    //     namedArgs: true,
    //     // carbon: true,
    //     rectorPreset: true,
    //     phpunitCodeQuality: true,
    //     phpunitNarrowAsserts: true,
    //     phpunitMockToStub: true,
    // )
    ->withSets([
        Guanguans\RectorRules\Set\SetList::ALL,
        PHPUnitSetList::ANNOTATIONS_TO_ATTRIBUTES,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
        PHPUnitSetList::PHPUNIT_NARROW_ASSERTS,
        PHPUnitSetList::PHPUNIT_MOCK_TO_STUB,
        DowngradeLevelSetList::DOWN_TO_PHP_74,
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::TYPE_DECLARATION,
        SetList::TYPE_DECLARATION_DOCBLOCKS,
        SetList::PRIVATIZATION,
        // SetList::NAMING,
        SetList::NAMED_ARGS,
        // SetList::CARBON,
        SetList::RECTOR_PRESET,
        SetList::PHP_POLYFILLS,
    ])
    ->withRules([
        GeneratorPropertyFetchToMethodCallRector::class,
        SortAssociativeArrayByKeyRector::class,
        // UpdateCodeSamplesRector::class,
    ])
    ->withConfiguredRule(AddNoinspectionDocblockToFileFirstStmtRector::class, [
        '*/tests/Fixer/*' => $inspections = [
            'AnonymousFunctionStaticInspection',
            'NullPointerExceptionInspection',
            'PhpPossiblePolymorphicInvocationInspection',
            'PhpUndefinedClassInspection',
            'PhpUnhandledExceptionInspection',
            'PhpVoidFunctionResultUsedInspection',
            'SqlResolve',
            'StaticClosureCanBeUsedInspection',
        ],
        '*/tests/Support/*' => $inspections,
    ])
    ->registerDecoratingNodeVisitor(ParentConnectingVisitor::class)
    ->withConfiguredRule(RenameToConventionalCaseNameRector::class, ['beforeEach', 'MIT'])
    ->withConfiguredRule(ReferenceNamespacedSymbolsRelativeToNamespacePrefixRector::class, [
        'namespacePrefixes' => [
            // 'Guanguans\\PhpCsFixerCustomFixers',
        ],
    ])
    ->withConfiguredRule(RenameFunctionRector::class, [
        'Illuminate\Support\php_binary' => 'Guanguans\PhpCsFixerCustomFixers\Support\php_binary',
    ])
    ->withSkip([
        DowngradeArraySpreadRector::class,
        DowngradeArrayIsListRector::class,
        DowngradeStrContainsRector::class,
        DowngradeStrEndsWithRector::class,
        DowngradeStrStartsWithRector::class,
    ])
    ->withSkip([
        PrivateToProtectedVisibilityForTraitRector::class,
        StringToClassConstantRector::class,

        LogicalToBooleanRector::class,
        NewlineBetweenClassLikeStmtsRector::class,
        PreferPHPUnitThisCallRector::class,
        SplitDoubleAssignRector::class,
    ])
    ->withSkip([
        RemoveUnusedPrivateMethodRector::class => [
            __DIR__.'/src/Fixer/*/*Fixer.php',
        ],
        RenameGarbageParamNameRector::class => [
            __DIR__.'/src/Fixer/Concern/CandidateOfAny.php',
        ],
        SortAssociativeArrayByKeyRector::class => [
            __DIR__.'/src/',
            __DIR__.'/tests/',
        ],
    ]);
