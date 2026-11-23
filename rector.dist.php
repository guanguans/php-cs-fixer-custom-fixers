<?php

/** @noinspection PhpInternalEntityUsedInspection */
/** @noinspection PhpUnhandledExceptionInspection */
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
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;
use Guanguans\RectorRules\Rector\Array_\SortListItemOfSameScalarTypeRector;
use Guanguans\RectorRules\Rector\ClassLike\RemoveAnnotationRector;
use Guanguans\RectorRules\Rector\ClassMethod\PrivateToProtectedVisibilityForTraitRector;
use Guanguans\RectorRules\Rector\FuncCall\FuncCallToStaticCallRector;
use Guanguans\RectorRules\Rector\FunctionLike\RenameGarbageParamNameRector;
use Guanguans\RectorRules\Rector\Scalar\ScalarValueToConstFetchRector;
use Guanguans\RectorRules\Rector\String_\StringToClassConstantRector;
use Guanguans\RectorRules\Support\Utils as RectorRulesUtils;
use Illuminate\Support\Str;
use PhpCsFixer\Preg;
use Rector\CodeQuality\Rector\LogicalAnd\LogicalToBooleanRector;
use Rector\CodingStyle\Rector\Assign\SplitDoubleAssignRector;
use Rector\CodingStyle\Rector\ClassLike\NewlineBetweenClassLikeStmtsRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\DowngradePhp74\Rector\Array_\DowngradeArraySpreadRector;
use Rector\DowngradePhp80\Rector\FuncCall\DowngradeStrContainsRector;
use Rector\DowngradePhp80\Rector\FuncCall\DowngradeStrEndsWithRector;
use Rector\DowngradePhp80\Rector\FuncCall\DowngradeStrStartsWithRector;
use Rector\DowngradePhp80\Rector\StaticCall\DowngradePhpTokenRector;
use Rector\DowngradePhp81\Rector\FuncCall\DowngradeArrayIsListRector;
use Rector\DowngradePhp83\Rector\FuncCall\DowngradeJsonValidateRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitThisCallRector;
use Rector\PHPUnit\PHPUnit60\Rector\ClassMethod\AddDoesNotPerformAssertionToNonAssertingTestRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Transform\ValueObject\FuncCallToStaticCall;
use Rector\TypeDeclarationDocblocks\Rector\Property\MergePhpstanDocTagIntoNativeRector;
use Rector\ValueObject\PhpVersion;

error_reporting(\E_ALL & ~\E_DEPRECATED & ~\E_USER_DEPRECATED);

return RectorConfig::configure()
    ->withPaths([...Utils::defaultRootDirectories(), ...Utils::defaultRootFiles()])
    ->withSkip(['*/Fixtures/*'])
    ->withSkip([
        DowngradeArrayIsListRector::class,
        DowngradeArraySpreadRector::class,
        DowngradeJsonValidateRector::class,
        DowngradePhpTokenRector::class,
        DowngradeStrContainsRector::class,
        DowngradeStrEndsWithRector::class,
        DowngradeStrStartsWithRector::class,
    ])
    ->withSkip([
        // PHP Fatal error:  Uncaught Error: Class 'WeakMap' not found in vendor/rector/rector/
        // vendor/rector/rector-phpunit/src/NodeAnalyzer/LifecycleMethodAssertAnalyzer.php:58
        AddDoesNotPerformAssertionToNonAssertingTestRector::class,

        /** @see SortListItemOfSameScalarTypeRector */
        LogicalToBooleanRector::class,
        NewlineBetweenClassLikeStmtsRector::class,
        PreferPHPUnitThisCallRector::class,
        SplitDoubleAssignRector::class,
    ])
    ->withSkip([
        MergePhpstanDocTagIntoNativeRector::class => [
            __DIR__.'/src/Support/SplFileInfo.php',
        ],
        PrivateToProtectedVisibilityForTraitRector::class => [
            __DIR__.'/src/Fixer/*Concern/Configurable*.php',
            __DIR__.'/src/Fixer/CommandLineTool/Concern/HasTemporaryMirrorFile.php',
        ],
        RemoveAnnotationRector::class => [
            __DIR__.'/src/Fixer/CommandLineTool/*Fixer.php',
        ],
        RemoveUnusedPrivateMethodRector::class => [
            __DIR__.'/src/Fixer/*/*Fixer.php',
        ],
        RenameGarbageParamNameRector::class => [
            __DIR__.'/src/Fixer/Concern/CandidateOfAny.php',
        ],
        ScalarValueToConstFetchRector::class => [
            __DIR__.'/.php-cs-fixer-non-php.php',
            __DIR__.'/src/Fixer/**Fixer.php',
            __DIR__.'/src/Fixer/Concern/Configurable.php',
            __DIR__.'/tests/Feature/AbstractSpecificFixerTestCase.php',
        ],
        SortAssociativeArrayByKeyRector::class => [
            __DIR__.'/tests/Support/UtilsTest.php',
        ],
        StringToClassConstantRector::class => [
            __DIR__.'/config/rules.php',
            __DIR__.'/config/set/guanguans.php',
        ],
    ])
    ->withCache(__DIR__.'/.build/rector/')
    // ->withoutParallel()
    ->withParallel()
    // ->withImportNames(importDocBlockNames: false, importShortClasses: false, removeUnusedImports: false)
    ->withImportNames(true, false, false, false)
    ->reportUnusedSkips()
    ->withFluentCallNewLine()
    ->withTreatClassesAsFinal()
    ->withTypeGuardedClasses([])
    // ->withAttributesSets(phpunit: true, all: true)
    // ->withComposerBased(phpunit: true/* , laravel: true */)
    ->withComposerBased(false, false, true)
    ->withPhpVersion(PhpVersion::PHP_74)
    ->withPhpLevel(PhpVersion::PHP_74)
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
        // UpdateCodeSamplesRector::class,
    ])
    ->withConfiguredRule(
        ScalarValueToConstFetchRector::class,
        RectorRulesUtils::configurationOfScalarValueToConstFetchRector()
    )
    ->withConfiguredRule(
        FuncCallToStaticCallRector::class,
        collect((new ReflectionClass(Preg::class))->getMethods(/* ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_STATIC */))
            ->filter(static fn (ReflectionMethod $reflectionMethod): bool => $reflectionMethod->isPublic() && $reflectionMethod->isStatic())
            ->map(static fn (ReflectionMethod $reflectionMethod): FuncCallToStaticCall => new FuncCallToStaticCall(
                (string) Str::of($reflectionMethod->getName())->snake()->prepend('preg_'),
                Preg::class,
                $reflectionMethod->getName()
            ))
            ->all()
    )
    ->withConfiguredRule(RenameFunctionRector::class, [
        'Illuminate\Support\php_binary' => 'Guanguans\PhpCsFixerCustomFixers\Support\php_binary',
    ]);
