<?php

/** @noinspection PhpUnusedAliasInspection */
/** @noinspection PhpDeprecationInspection */
/** @noinspection PhpInternalEntityUsedInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

/**
 * Copyright (c) 2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

use Ergebnis\Rector\Rules\Arrays\SortAssociativeArrayByKeyRector;
use Guanguans\MonorepoBuilderWorker\Support\Rectors\AddNoinspectionsDocCommentToDeclareRector;
use Guanguans\MonorepoBuilderWorker\Support\Rectors\NewExceptionToNewAnonymousExtendsExceptionImplementsRector;
use Guanguans\MonorepoBuilderWorker\Support\Rectors\RemoveNamespaceRector;
use Guanguans\PhpCsFixerCustomFixers\Contract\ThrowableContract;
use Guanguans\PhpCsFixerCustomFixers\Support\Rectors\UpdateCodeSamplesRector;
use Illuminate\Support\Str;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\LogicalAnd\LogicalToBooleanRector;
use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\Assign\SplitDoubleAssignRector;
use Rector\CodingStyle\Rector\ClassLike\NewlineBetweenClassLikeStmtsRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\CodingStyle\Rector\Encapsed\WrapEncapsedVariableInCurlyBracesRector;
use Rector\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector;
use Rector\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassLike\RemoveAnnotationRector;
use Rector\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector;
use Rector\DowngradePhp80\Rector\FuncCall\DowngradeStrContainsRector;
use Rector\DowngradePhp80\Rector\FuncCall\DowngradeStrEndsWithRector;
use Rector\DowngradePhp80\Rector\FuncCall\DowngradeStrStartsWithRector;
use Rector\EarlyReturn\Rector\If_\ChangeOrIfContinueToMultiContinueRector;
use Rector\EarlyReturn\Rector\Return_\ReturnBinaryOrToEarlyReturnRector;
use Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Transform\Rector\FuncCall\FuncCallToStaticCallRector;
use Rector\Transform\Rector\StaticCall\StaticCallToFuncCallRector;
use Rector\Transform\ValueObject\FuncCallToStaticCall;
use Rector\Transform\ValueObject\StaticCallToFuncCall;
use Rector\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector;
use Rector\ValueObject\PhpVersion;
use Rector\ValueObject\Visibility;
use Rector\Visibility\Rector\ClassMethod\ChangeMethodVisibilityRector;
use Rector\Visibility\ValueObject\ChangeMethodVisibility;
use function Guanguans\PhpCsFixerCustomFixers\Support\classes;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/examples/',
        __DIR__.'/src/',
        __DIR__.'/tests/',
        __DIR__.'/composer-bump',
    ])
    ->withRootFiles()
    ->withAutoloadPaths([
        // (new ReflectionClass(ThrowableContract::class))->getFileName(),
    ])
    ->withBootstrapFiles([
        // __DIR__.'/vendor/symplify/monorepo-builder/vendor/autoload.php',
        // __DIR__.'/vendor/symplify/monorepo-builder/vendor/scoper-autoload.php',
    ])
    ->withSkip([
        '**/Fixtures/*',
        __DIR__.'/_ide_helper.php',
        __DIR__.'/tests.php',
        // __FILE__,
    ])
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
    //     // carbon: true,
    // )
    ->withSets([
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
    ])
    ->withRules([
        SortAssociativeArrayByKeyRector::class,
        // UpdateCodeSamplesRector::class,

        // ArraySpreadInsteadOfArrayMergeRector::class,
        JsonThrowOnErrorRector::class,
        StaticArrowFunctionRector::class,
        StaticClosureRector::class,
    ])
    // ->withConfiguredRule(AddNoinspectionsDocCommentToDeclareRector::class, [
    //     'AnonymousFunctionStaticInspection',
    //     'NullPointerExceptionInspection',
    //     'PhpPossiblePolymorphicInvocationInspection',
    //     'PhpUndefinedClassInspection',
    //     'PhpUnhandledExceptionInspection',
    //     'PhpVoidFunctionResultUsedInspection',
    //     'SqlResolve',
    //     'StaticClosureCanBeUsedInspection',
    // ])
    // ->withConfiguredRule(NewExceptionToNewAnonymousExtendsExceptionImplementsRector::class, [
    //     ThrowableContract::class,
    // ])
    // ->withConfiguredRule(RemoveNamespaceRector::class, [
    //     'Guanguans\PhpCsFixerCustomFixersTests',
    // ])
    ->withConfiguredRule(RemoveAnnotationRector::class, [
        'codeCoverageIgnore',
        'inheritDoc',
        'phpstan-ignore',
        'phpstan-ignore-next-line',
        'psalm-suppress',
    ])
    ->withConfiguredRule(StaticCallToFuncCallRector::class, [
        // new StaticCallToFuncCall(Str::class, 'of', 'str'),
    ])
    ->withConfiguredRule(FuncCallToStaticCallRector::class, [
        new FuncCallToStaticCall('str', Str::class, 'of'),
    ])
    // ->withConfiguredRule(
    //     AnnotationToAttributeRector::class,
    //     classes(static fn (string $class): bool => str_starts_with($class, 'PhpBench\Attributes'))
    //         ->filter(static fn (\ReflectionClass $reflectionClass): bool => $reflectionClass->isInstantiable())
    //         ->map(static fn (\ReflectionClass $reflectionClass): AnnotationToAttribute => new AnnotationToAttribute(
    //             $reflectionClass->getShortName(),
    //             $reflectionClass->getName(),
    //             [],
    //             true
    //         ))
    //         ->all(),
    // )
    ->withConfiguredRule(
        ChangeMethodVisibilityRector::class,
        classes(static fn (string $class, string $file): bool => str_starts_with($class, 'Guanguans\PhpCsFixerCustomFixers'))
            ->filter(static fn (ReflectionClass $reflectionClass): bool => $reflectionClass->isTrait())
            ->map(
                static fn (ReflectionClass $reflectionClass): array => collect($reflectionClass->getMethods(ReflectionMethod::IS_PRIVATE))
                    ->reject(static fn (ReflectionMethod $reflectionMethod): bool => $reflectionMethod->isFinal() || $reflectionMethod->isInternal())
                    ->map(static fn (ReflectionMethod $reflectionMethod): ChangeMethodVisibility => new ChangeMethodVisibility(
                        $reflectionClass->getName(),
                        $reflectionMethod->getName(),
                        Visibility::PROTECTED
                    ))
                    ->all()
            )
            ->flatten()
            // ->dd()
            ->all(),
    )
    ->withConfiguredRule(
        RenameFunctionRector::class,
        [
            'Illuminate\Support\php_binary' => 'Guanguans\PhpCsFixerCustomFixers\Support\php_binary',
            'Pest\Faker\fake' => 'fake',
            'Pest\Faker\faker' => 'fake',
            'test' => 'it',
        ] + array_reduce(
            [
                'classes',
            ],
            static function (array $carry, string $func): array {
                /** @see https://github.com/laravel/framework/blob/11.x/src/Illuminate/Support/functions.php */
                $carry[$func] = "Guanguans\\PhpCsFixerCustomFixers\\Support\\$func";

                return $carry;
            },
            []
        )
    )
    ->withSkip([
        DowngradeStrContainsRector::class,
        DowngradeStrEndsWithRector::class,
        DowngradeStrStartsWithRector::class,
    ])
    ->withSkip([
        ChangeOrIfContinueToMultiContinueRector::class,
        EncapsedStringsToSprintfRector::class,
        ExplicitBoolCompareRector::class,
        LogicalToBooleanRector::class,
        NewlineAfterStatementRector::class,
        NewlineBetweenClassLikeStmtsRector::class,
        ReturnBinaryOrToEarlyReturnRector::class,
        SplitDoubleAssignRector::class,
        WrapEncapsedVariableInCurlyBracesRector::class,
    ])
    ->withSkip([
        FunctionLikeToFirstClassCallableRector::class => [
            __DIR__.'/src/Support/helpers.php',
        ],
        ParamTypeByMethodCallTypeRector::class => [
            __DIR__.'/src/Fixer/BladeFixer.php',
        ],
        RemoveAlwaysTrueIfConditionRector::class => [
        ],
        StaticArrowFunctionRector::class => $staticClosureSkipPaths = [
            __DIR__.'/tests/',
        ],
        StaticClosureRector::class => $staticClosureSkipPaths,
        SortAssociativeArrayByKeyRector::class => [
            __DIR__.'/examples/',
            __DIR__.'/src/',
            __DIR__.'/tests/',
            __FILE__,
        ],
        // AddNoinspectionsDocCommentToDeclareRector::class => [
        //     __DIR__.'/examples/',
        //     __DIR__.'/src/',
        //     // __DIR__.'/tests/',
        //     ...$rootFiles = array_filter(
        //         glob(__DIR__.'/{*,.*}.php', \GLOB_BRACE),
        //         static fn (string $filename): bool => !\in_array(
        //             $filename,
        //             [
        //                 __DIR__.'/_ide_helper.php',
        //                 __DIR__.'/tests.php',
        //             ],
        //             true
        //         )
        //     ),
        //     __DIR__.'/composer-bump',
        // ],
        // NewExceptionToNewAnonymousExtendsExceptionImplementsRector::class => [
        //     __DIR__.'/src/Support/Rectors/',
        // ],
        // RemoveNamespaceRector::class => [
        //     __DIR__.'/examples/',
        //     __DIR__.'/src/',
        //     // __DIR__.'/tests/',
        //     ...$rootFiles,
        //     __DIR__.'/composer-bump',
        //     __DIR__.'/tests/TestCase.php',
        // ],
    ]);
