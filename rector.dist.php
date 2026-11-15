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
use Guanguans\RectorRules\Rector\File\AddNoinspectionDocblockToFileFirstStmtRector;
use Guanguans\RectorRules\Rector\FunctionLike\RenameGarbageParamNameRector;
use Guanguans\RectorRules\Rector\Name\RenameToConventionalCaseNameRector;
use Guanguans\RectorRules\Rector\Scalar\ScalarValueToConstFetchRector;
use Guanguans\RectorRules\Rector\String_\StringToClassConstantRector;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\Float_;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
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
use Rector\DowngradePhp83\Rector\FuncCall\DowngradeJsonValidateRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitThisCallRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Transform\ValueObject\ScalarValueToConstFetch;
use Rector\Transform\ValueObject\StringToClassConstant;
use Rector\ValueObject\PhpVersion;
use function Guanguans\RectorRules\Support\classes;

error_reporting(\E_ALL & ~\E_DEPRECATED & ~\E_USER_DEPRECATED);

return RectorConfig::configure()
    ->withPaths([...Utils::defaultRootDirectories(), ...Utils::defaultRootFiles()])
    ->withSkip(['*/Fixtures/*'])
    ->withSkip([
        DowngradeArrayIsListRector::class,
        DowngradeArraySpreadRector::class,
        DowngradeStrContainsRector::class,
        DowngradeStrEndsWithRector::class,
        DowngradeStrStartsWithRector::class,
    ])
    ->withSkip([
        // Skip common rules.
        LogicalToBooleanRector::class,
        NewlineBetweenClassLikeStmtsRector::class,
        PreferPHPUnitThisCallRector::class,
        SplitDoubleAssignRector::class,
    ])
    ->withSkip([
        DowngradeJsonValidateRector::class => [
            __DIR__.'/src/Support/Utils.php',
        ],
        PrivateToProtectedVisibilityForTraitRector::class => [
            __DIR__.'/src/Fixer/CommandLineTool/Concern/HasTemporaryMirrorFile.php',
            __DIR__.'/src/Fixer/Concern/Configurable.php',
            __DIR__.'/src/Fixer/Concern/ConfigurableOf*.php',
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
            __DIR__.'/.php-cs-fixer-custom.php',
            __DIR__.'/src/Fixer/**Fixer.php',
            __DIR__.'/src/Fixer/Concern/Configurable.php',
            __DIR__.'/tests/Feature/AbstractSpecificFixerTestCase.php',
            __DIR__.'/tests/Feature/CommandLineTool/GenericsFixerTest.php',
            __DIR__.'/tests/Fixer/Concern/ConcernTest.php',
        ],
        SortAssociativeArrayByKeyRector::class => [
            __DIR__.'/src/',
            __DIR__.'/tests/',
        ],
        SortListItemOfSameScalarTypeRector::class => [
            __DIR__.'/config/rules.php',
            __DIR__.'/config/set/guanguans.php',
            __DIR__.'/src/Fixer/',
            __DIR__.'/tests/Feature/*/*FixerTest.php',
            __DIR__.'/tests/Feature/AbstractSpecificFixerTestCase.php',
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
    // ->withConfiguredRule(
    //     StringToClassConstantRector::class,
    //     classes(static fn (string $class): bool => str_starts_with($class, 'Guanguans\PhpCsFixerCustomFixers'))->reduce(
    //         static fn (array $carry, ReflectionClass $reflectionClass): array => array_merge(
    //             $carry,
    //             array_map(
    //                 static fn (
    //                     string $value,
    //                     string $name
    //                 ): StringToClassConstant => new StringToClassConstant($value, $reflectionClass->getName(), $name),
    //                 $constants = array_filter(
    //                     $reflectionClass->getConstants(/* ReflectionClassConstant::IS_PUBLIC */),
    //                     static fn (
    //                         $value,
    //                         string $name
    //                     ): bool => \is_string($value) && $reflectionClass->getReflectionConstant($name)->isPublic(),
    //                     \ARRAY_FILTER_USE_BOTH
    //                 ),
    //                 array_keys($constants),
    //             ),
    //         ),
    //         [],
    //     )
    // )
    ->withConfiguredRule(
        ScalarValueToConstFetchRector::class,
        classes(static fn (string $class): bool => str_starts_with($class, 'Guanguans\PhpCsFixerCustomFixers'))->reduce(
            static fn (array $carry, ReflectionClass $reflectionClass): array => array_merge(
                $carry,
                array_map(
                    static fn ($value, string $name): ScalarValueToConstFetch => new ScalarValueToConstFetch(
                        // new (match (true) {
                        //     \is_float($value) => Float_::class,
                        //     \is_int($value) => Int_::class,
                        //     \is_string($value) => String_::class,
                        // })($value),
                        (static function (/* float|int|string */ $value): object {
                            switch (true) {
                                case \is_float($value):
                                    return new Float_($value);
                                case \is_int($value):
                                    return new Int_($value);
                                case \is_string($value):
                                default:
                                    return new String_($value);
                            }
                        })($value),
                        new ClassConstFetch(new FullyQualified($reflectionClass->getName()), new Identifier($name))
                    ),
                    $constants = array_filter(
                        $reflectionClass->getConstants(/* ReflectionClassConstant::IS_PUBLIC */),
                        static fn ($value, string $name): bool => (\is_float($value) || \is_int($value) || \is_string($value))
                            && $reflectionClass->getReflectionConstant($name)->isPublic(),
                        \ARRAY_FILTER_USE_BOTH
                    ),
                    array_keys($constants)
                )
            ),
            [],
        )
    )
    ->withConfiguredRule(RenameToConventionalCaseNameRector::class, ['MIT', 'beforeEach'])
    ->withConfiguredRule(RenameFunctionRector::class, [
        'Illuminate\Support\php_binary' => 'Guanguans\PhpCsFixerCustomFixers\Support\php_binary',
    ]);
