<?php

/** @noinspection PhpDeprecationInspection */
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

use Ergebnis\Rector\Rules\Arrays\SortAssociativeArrayByKeyRector;
use Ergebnis\Rector\Rules\Faker\GeneratorPropertyFetchToMethodCallRector;
use Ergebnis\Rector\Rules\Files\ReferenceNamespacedSymbolsRelativeToNamespacePrefixRector;
use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyCommandContract;
use Guanguans\PhpCsFixerCustomFixers\Support\Rector\UpdateCodeSamplesRector;
use Guanguans\RectorRules\Rector\ClassMethod\PrivateToProtectedVisibilityForTraitRector;
use Guanguans\RectorRules\Rector\File\AddNoinspectionDocblockToFileFirstStmtRector;
use Guanguans\RectorRules\Rector\FunctionLike\RenameGarbageParamNameRector;
use Guanguans\RectorRules\Rector\Name\RenameToConventionalCaseNameRector;
use PhpParser\NodeVisitor\ParentConnectingVisitor;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\LogicalAnd\LogicalToBooleanRector;
use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\Assign\SplitDoubleAssignRector;
use Rector\CodingStyle\Rector\ClassLike\NewlineBetweenClassLikeStmtsRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\CodingStyle\Rector\Encapsed\WrapEncapsedVariableInCurlyBracesRector;
use Rector\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector;
use Rector\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassLike\RemoveAnnotationRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\DowngradePhp80\Rector\FuncCall\DowngradeStrContainsRector;
use Rector\DowngradePhp80\Rector\FuncCall\DowngradeStrEndsWithRector;
use Rector\DowngradePhp80\Rector\FuncCall\DowngradeStrStartsWithRector;
use Rector\EarlyReturn\Rector\If_\ChangeOrIfContinueToMultiContinueRector;
use Rector\EarlyReturn\Rector\Return_\ReturnBinaryOrToEarlyReturnRector;
use Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use Rector\Transform\Rector\Scalar\ScalarValueToConstFetchRector;
use Rector\Transform\Rector\String_\StringToClassConstantRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector;
use Rector\ValueObject\PhpVersion;
use function Guanguans\RectorRules\Support\classes;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/config/',
        __DIR__.'/src/',
        __DIR__.'/tests/',
        __DIR__.'/composer-bump',
    ])
    ->withRootFiles()
    ->withSkip(['**/Fixtures/*', __DIR__.'/tests.php'])
    ->withCache(__DIR__.'/.build/rector/')
    // ->withoutParallel()
    ->withParallel()
    // ->withImportNames(importDocBlockNames: false, importShortClasses: false)
    ->withImportNames(true, false, false)
    // ->withImportNames(importNames: false)
    // ->withEditorUrl()
    ->withFluentCallNewLine()
    ->withTreatClassesAsFinal()
    // ->withAttributesSets(phpunit: true, all: true)
    // ->withComposerBased(phpunit: true/* , laravel: true */)
    ->withComposerBased(false, false, true)
    ->withPhpVersion(PhpVersion::PHP_74)
    // ->withDowngradeSets(php74: true)
    // ->withDowngradeSets(php74: true)
    // ->withPhpSets(php74: true)
    ->withPhp74Sets()
    // ->withPreparedSets(
    //     deadCode: true,
    //     codeQuality: true,
    //     codingStyle: true,
    //     typeDeclarations: true,
    //     typeDeclarationDocblocks: true,
    //     privatization: true,
    //     naming: true,
    //     instanceOf: true,
    //     earlyReturn: true,
    //     // strictBooleans: true,
    //     // carbon: true,
    //     rectorPreset: true,
    //     phpunitCodeQuality: true,
    // )
    ->withSets([
        Guanguans\RectorRules\Set\SetList::ALL,
        PHPUnitSetList::PHPUNIT_90,
        DowngradeLevelSetList::DOWN_TO_PHP_74,
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::TYPE_DECLARATION,
        SetList::TYPE_DECLARATION_DOCBLOCKS,
        SetList::PRIVATIZATION,
        // SetList::NAMING,
        SetList::INSTANCEOF,
        SetList::EARLY_RETURN,
        // SetList::CARBON,
        // SetList::ASSERT,
        SetList::PHP_POLYFILLS,
        SetList::RECTOR_PRESET,
    ])
    ->withRules([
        // ArraySpreadInsteadOfArrayMergeRector::class,
        EnumCaseToPascalCaseRector::class,
        GeneratorPropertyFetchToMethodCallRector::class,
        JsonThrowOnErrorRector::class,
        SafeDeclareStrictTypesRector::class,
        SortAssociativeArrayByKeyRector::class,
        StaticArrowFunctionRector::class,
        StaticClosureRector::class,
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
    ->withConfiguredRule(RenameFunctionRector::class, [
        'Illuminate\Support\php_binary' => 'Guanguans\PhpCsFixerCustomFixers\Support\php_binary',
    ])
    ->withConfiguredRule(ReferenceNamespacedSymbolsRelativeToNamespacePrefixRector::class, [
        'namespacePrefixes' => [
            // 'Guanguans\\PhpCsFixerCustomFixers',
        ],
    ])
    ->withSkip([
        DowngradeStrContainsRector::class,
        DowngradeStrEndsWithRector::class,
        DowngradeStrStartsWithRector::class,
    ])
    ->withSkip([
        PrivateToProtectedVisibilityForTraitRector::class,
        ScalarValueToConstFetchRector::class,
        StringToClassConstantRector::class,

        ChangeOrIfContinueToMultiContinueRector::class,
        DisallowedEmptyRuleFixerRector::class,
        EncapsedStringsToSprintfRector::class,
        ExplicitBoolCompareRector::class,
        LogicalToBooleanRector::class,
        NewlineBetweenClassLikeStmtsRector::class,
        ReturnBinaryOrToEarlyReturnRector::class,
        SplitDoubleAssignRector::class,
        WrapEncapsedVariableInCurlyBracesRector::class,
    ])
    ->withSkip([
        JsonThrowOnErrorRector::class => [
            __DIR__.'/src/Support/Utils.php',
        ],
        RemoveAnnotationRector::class => classes(static fn (string $class): bool => str_starts_with(
            $class,
            'Guanguans\PhpCsFixerCustomFixers\Fixer'
        ))
            ->filter(static fn (ReflectionClass $reflectionClass): bool => $reflectionClass->isSubclassOf(DependencyCommandContract::class))
            ->map(static fn (ReflectionClass $reflectionClass): string => $reflectionClass->getFileName())
            // ->dd()
            ->all(),
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
        StaticArrowFunctionRector::class => $staticClosureSkipPaths = [
            __DIR__.'/tests/*Test.php',
            __DIR__.'/tests/Pest.php',
        ],
        StaticClosureRector::class => $staticClosureSkipPaths,
    ]);
