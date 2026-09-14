<?php

/** @noinspection EfferentObjectCouplingInspection */
/** @noinspection PhpDeprecationInspection */
/** @noinspection PhpInternalEntityUsedInspection */
/** @noinspection PhpUnused */

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

namespace Guanguans\PhpCsFixerCustomFixers\Support;

use Composer\Script\Event;
use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyCommandContract;
use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyNameContract;
use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixers;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;
use PhpCsFixer\FixerDefinition\CodeSampleInterface;
use PhpCsFixer\FixerDefinition\VersionSpecificCodeSampleInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet\RuleSet;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Utils;
use PhpCsFixer\WhitespacesFixerConfig;
use Rector\Config\RectorConfig;
use Rector\DependencyInjection\LazyContainerFactory;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\StrictUnifiedDiffOutputBuilder;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * @see https://github.com/laravel/framework/blob/12.x/src/Illuminate/Foundation/ComposerScripts.php
 *
 * @internal
 *
 * @property \Symfony\Component\Console\Output\ConsoleOutput $output
 *
 * @method void configureIO(InputInterface $input, OutputInterface $output)
 */
final class ComposerScripts
{
    /**
     * @see https://github.com/ecsphp/ecs/blob/13.2.19/src/Config/ECSConfig.php#L176-L191
     * @see https://github.com/ecsphp/ecs/pull/57
     * @see ECSConfig::dynamicSets()
     *
     * @return array<class-string<\PhpCsFixer\Fixer\FixerInterface>, mixed> $configuration
     *
     * @noinspection RepetitiveMethodCallsInspection
     */
    public static function toEcsConfiguration(Event $event): int
    {
        self::requireAutoload($event);

        collect([
            // 'unary_operator_spaces' => [
            //     'only_dec_inc' => true,
            // ],
            // 'no_superfluous_phpdoc_tags' => [
            //     'allow_hidden_params' => false,
            //     'allow_mixed' => true,
            //     'allow_unused_params' => false,
            //     'remove_inheritdoc' => false,
            // ],
            // 'phpdoc_separation' => [
            //     'groups' => [
            //         [
            //             'deprecated',
            //         ],
            //         [
            //             'link',
            //             'see',
            //             'since',
            //         ],
            //         [
            //             'author',
            //             'copyright',
            //             'license',
            //         ],
            //         [
            //             'category',
            //             'package',
            //             'subpackage',
            //         ],
            //         [
            //             'property',
            //             'property-read',
            //             'property-write',
            //         ],
            //     ],
            //     'skip_unlisted_annotations' => false,
            // ],
            // 'string_implicit_backslashes' => [
            //     'double_quoted' => 'escape',
            //     'heredoc' => 'escape',
            //     'single_quoted' => 'ignore',
            // ],
            // 'multiline_promoted_properties' => [
            //     'keep_blank_lines' => false,
            //     'minimum_number_of_parameters' => 2,
            // ],
            // 'ordered_types' => [
            //     'case_sensitive' => false,
            //     'null_adjustment' => 'always_first',
            //     'sort_algorithm' => 'alpha',
            // ],
            // 'single_line_comment_style' => [
            //     'comment_types' => [
            //         'hash',
            //     ],
            // ],
            // 'final_public_method_for_abstract_class' => true,
            'fopen_flags' => [
                'b_mode' => true,
            ],
        ])
            ->reject(static fn ($_, string $name): bool => str_starts_with($name, '@'))
            ->tap(static function () use (&$fixerClassesMap): void {
                $fixerFactory = new FixerFactory;
                $fixerFactory->registerBuiltInFixers();

                $fixerClassesMap = array_reduce(
                    $fixerFactory->getFixers(),
                    static function (array $carry, FixerInterface $fixer): array {
                        $carry[$fixer->getName()] = \get_class($fixer);

                        return $carry;
                    },
                    [],
                );
            })
            ->reduce(
                static function (Collection $carry, $config, string $name) use ($fixerClassesMap): Collection {
                    $carry[$fixerClassesMap[$name]] = $config;

                    return $carry;
                },
                collect(),
            )
            ->tap(static function (Collection $configuration) use ($event): void {
                $event->getIO()->warning(
                    $configuration->filter(static fn ($config): bool => \is_array($config))->reduce(
                        static fn (string $code, array $config, string $fixerClass): string => $code.\sprintf(
                            <<<'PHP'

                                ->withConfiguredRule(%s, %s)
                                PHP,
                            var_export($fixerClass, true),
                            var_export($config, true)
                        ),
                        ''
                    )
                );
                $event->getIO()->warning('');

                $event->getIO()->warning(
                    var_export($configuration->filter(static fn ($config): bool => true === $config)->keys()->all(), true)
                );
                $event->getIO()->warning('');

                $event->getIO()->warning(
                    var_export($configuration->filter(static fn ($config): bool => false === $config)->keys()->all(), true)
                );
                $event->getIO()->warning('');
            });

        $event->getIO()->info('No errors');

        return 0;
    }

    /**
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    public static function installCommandLineTools(Event $event): int
    {
        self::requireAutoload($event, true);

        collect(Fixers::make())
            ->filter(static fn (AbstractFixer $fixer): bool => $fixer instanceof DependencyCommandContract)
            ->groupBy(static function (AbstractFixer $fixer) {
                $command = Str::of($fixer->dependencyCommand());

                if (!$command->contains(' && ') && !$command->startsWith(['go install'])) {
                    return $command->explode(' ', 3)->take(2)->implode(' ');
                }

                return (string) $command;
            })
            ->mapWithKeys(static fn (Collection $fixers): array => [
                \sprintf(
                    'Installing command line %s for %s:',
                    $fixers->containsOneItem() ? 'tool' : 'tools',
                    $fixers->map(static fn (AbstractFixer $fixer): string => "`{$fixer->getShortClassName()}`")->implode(' ')
                ) => $fixers->skip(1)
                    ->map(static fn (AbstractFixer $fixer): string => Str::of($fixer->dependencyCommand())->explode(' ')->last())
                    ->prepend($fixers->first()->dependencyCommand())
                    ->implode(' '),
            ])
            ->sort()
            ->each(
                static function (string $command, string $description) use ($event): void {
                    $event->getIO()->warning($description.\PHP_EOL.$command);

                    if (\Guanguans\PhpCsFixerCustomFixers\Support\Utils::hasParameterOption('--dry-run', true)) {
                        return;
                    }

                    Process::fromShellCommandline($command)
                        ->setTimeout(300)
                        ->mustRun(
                            \Guanguans\PhpCsFixerCustomFixers\Support\Utils::isDebug()
                            ? static function (string $type, string $buffer) use ($event): void {
                                Process::ERR === $type
                                    ? $event->getIO()->writeError($buffer)
                                    : $event->getIO()->write($buffer);
                            }
                            : null
                        );
                }
            );

        $event->getIO()->info('No errors');

        return 0;
    }

    public static function checkDocument(Event $event): int
    {
        self::requireAutoload($event);

        $extensions = collect(Fixers::make()->extensions())
            ->reject(static fn (string $ext): bool => \in_array(
                $ext,
                [
                    '*',
                    'env.example',
                    'markdown',
                    'php',
                    'xml.dist',
                    'yml',
                    'zh_CN.md',
                ],
                true
            ))
            ->all();

        file_put_contents(
            __DIR__.'/../../tests.check-document',
            implode(\PHP_EOL.\PHP_EOL, array_merge(
                $descriptionContents = [implode(',', $extensions), implode('、', $extensions)],
                [
                    $keywordContent = trim(
                        array_reduce(
                            collect(Fixers::make()->getDependencyNames())
                                ->reject(static fn (string $dependencyName): bool => \in_array(
                                    $dependencyName,
                                    [
                                        'dependencyName',
                                        'dependencyName',
                                    ],
                                    true
                                ))
                                ->map(
                                    static fn (string $dependencyName): string => (string) Str::of($dependencyName)
                                        ->replace('/', '-')
                                        ->slug()
                                )
                                ->all(),
                            static fn (string $carry, string $platform): string => $carry."        \"$platform\",\n",
                            ''
                        ),
                        ",\n"
                    ),
                ]
            ))
        );

        $composerContent = file_get_contents(__DIR__.'/../../composer.json');

        foreach ($descriptionContents as $descriptionContent) {
            if (!str_contains($composerContent, $descriptionContent)) {
                $event->getIO()->error("The description of composer.json must contain: \n```\n$descriptionContent\n```");

                exit(1);
            }
        }

        if (!str_contains($composerContent, $keywordContent)) {
            $event->getIO()->error("The keywords of composer.json must contain: \n```\n$keywordContent\n```");

            exit(1);
        }

        $readmeContent = file_get_contents(__DIR__.'/../../README.md');

        foreach ($descriptionContents as $descriptionContent) {
            if (!str_contains($readmeContent, $descriptionContent)) {
                $event->getIO()->error("The description of README.md must contain: \n```\n$descriptionContent\n```");

                exit(1);
            }
        }

        $event->getIO()->info('No errors');

        return 0;
    }

    /**
     * @see https://github.com/symplify/rule-doc-generator/blob/main/src/Command/GenerateCommand.php
     * @see \Composer\Util\Silencer
     *
     * @throws \ReflectionException
     */
    public static function updateFixersDocument(Event $event): int
    {
        self::requireAutoload($event, true);
        $event->getIO()->warning('Updating fixers document in README.md...');
        assert_options(\ASSERT_BAIL, 1);

        $updatedContents = preg_replace(
            '#'.preg_quote($start = '<!-- fixers-document:start -->', '#').'(.*?)'
            .preg_quote($end = '<!-- fixers-document:end -->', '#').'#s',
            $start.\PHP_EOL.self::fixersDocument().\PHP_EOL.$end,
            file_get_contents($path = getcwd().\DIRECTORY_SEPARATOR.'README.md')
        );

        \assert(\is_string($updatedContents));
        file_put_contents($path, $updatedContents);
        $event->getIO()->info('No errors');

        return 0;
    }

    public static function makeRectorConfig(): RectorConfig
    {
        static $rectorConfig;

        return $rectorConfig ??= (new LazyContainerFactory)->create();
    }

    /**
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    private static function requireAutoload(Event $event, ?bool $enableDebugging = null): void
    {
        $enableDebugging ??= (new ArgvInput)->hasParameterOption('-vvv', true);
        $enableDebugging and $event->getIO()->enableDebugging(microtime(true));
        (fn () => $this->output->setVerbosity(OutputInterface::VERBOSITY_DEBUG))->call($event->getIO());

        require_once $event->getComposer()->getConfig()->get('vendor-dir').\DIRECTORY_SEPARATOR.'autoload.php';
    }

    /**
     * @see https://github.com/kubawerlos/php-cs-fixer-custom-fixers/blob/main/.dev-tools/src/Readme/ReadmeCommand.php
     *
     * @throws \ReflectionException
     */
    private static function fixersDocument(): string
    {
        return (string) collect(new Fixers)
            ->reduce(
                static fn (Stringable $doc, AbstractFixer $fixer): Stringable => $doc
                    ->tap(static function () use ($fixer): void {
                        if ($fixer instanceof WhitespacesAwareFixerInterface) {
                            $fixer->setWhitespacesConfig(new WhitespacesFixerConfig);
                        }
                    })
                    ->append("\n\n<details>")
                    ->append(
                        \sprintf("\n<summary><b>%s</b></summary>", $fixer->getShortClassName()),
                        \sprintf("\n\n%s", self::summaryFor($fixer)),
                    )
                    ->when(
                        $fixer instanceof DeprecatedFixerInterface,
                        static function (Stringable $doc) use ($fixer): Stringable {
                            \assert($fixer instanceof DeprecatedFixerInterface);

                            return $doc->append(\sprintf(
                                "\n\nDeprecated: use `%s` instead.",
                                collect((new FixerFactory)
                                    ->registerBuiltInFixers()
                                    ->registerCustomFixers(new Fixers)
                                    ->useRuleSet(new RuleSet(array_combine(
                                        $successorsNames = $fixer->getSuccessorsNames(),
                                        array_pad([], \count($successorsNames), true)
                                    )))
                                    ->getFixers())
                                    ->map(
                                        static fn (FixerInterface $fixer): string => $fixer instanceof AbstractFixer
                                            ? $fixer->getShortClassName()
                                            : $fixer->getName()
                                    )
                                    ->implode('`, `')
                            ));
                        }
                    )
                    ->when(
                        $fixer->isRisky(),
                        static fn (Stringable $doc): Stringable => $doc->append(
                            \sprintf("\n\nRisky: %s", lcfirst($fixer->getDefinition()->getRiskyDescription()))
                        )
                    )
                    ->when(
                        $fixer instanceof ConfigurableFixerInterface,
                        static function (Stringable $doc) use ($fixer): Stringable {
                            \assert($fixer instanceof ConfigurableFixerInterface);

                            return collect($fixer->getConfigurationDefinition()->getOptions())->reduce(
                                static fn (Stringable $doc, FixerOptionInterface $option): Stringable => $doc->append(
                                    \sprintf(
                                        "\n- `%s` (`%s`): %s; defaults to `%s`",
                                        $option->getName(),
                                        implode(
                                            '`, `',
                                            array_map(
                                                static fn ($value): string => Utils::toString(
                                                    \is_string($value) ? addcslashes($value, "\t\n\r\0\x0B") : $value
                                                ),
                                                (array) $option->getAllowedValues()
                                            ) ?: $option->getAllowedTypes()
                                        ),
                                        lcfirst(rtrim($option->getDescription(), '.')),
                                        Utils::toString(
                                            \is_string($default = $option->getDefault())
                                                ? addcslashes($default, "\t\n\r\0\x0B")
                                                : $default
                                        ),
                                    )
                                ),
                                $doc->append("\n\nConfiguration options:\n")
                            );
                        }
                    )
                    ->pipe(
                        static fn (Stringable $doc): Stringable => collect($fixer->getDefinition()->getCodeSamples())->reduce(
                            static function (Stringable $doc, CodeSampleInterface $codeSample) use (&$index, $fixer): Stringable {
                                $index = ($index ?? 0) + 1;

                                if (
                                    $codeSample instanceof VersionSpecificCodeSampleInterface
                                    && !$codeSample->isSuitableFor(\PHP_VERSION_ID)
                                ) {
                                    return $doc;
                                }

                                $configuration = [];

                                if ($fixer instanceof ConfigurableFixerInterface) {
                                    $fixer->configure($configuration = ($codeSample->getConfiguration() ?? []));
                                }

                                $tokens = tap(
                                    Tokens::fromCode($code = $codeSample->getCode()),
                                    static function (Tokens $tokens) use ($fixer): void {
                                        $fixer->fix($fixer->makeDummySplFileInfo(), $tokens);
                                    }
                                );

                                return $doc->append(
                                    \sprintf(
                                        "\n\nSample$index: configuration(`%s`)",
                                        [] === $configuration ? 'default' : Utils::toString($configuration)
                                    ),
                                    \sprintf("\n\n```diff\n%s\n```", self::diff($code, $tokens->generateCode()))
                                );
                            },
                            $doc
                        )
                    )
                    ->append("\n</details>"),
                Str::of('')
            )
            ->trim()
            ->replace(php_binary(), 'php')
            ->replace(
                $searches = ['<pre>', '</pre>'],
                array_map(
                    static fn (string $search): string => htmlspecialchars($search, \ENT_QUOTES | \ENT_SUBSTITUTE),
                    $searches
                ),
            );
    }

    private static function summaryFor(AbstractFixer $fixer): string
    {
        $summary = $fixer->getDefinition()->getSummary();

        if (!$fixer instanceof DependencyNameContract) {
            return $summary;
        }

        $see = \Guanguans\PhpCsFixerCustomFixers\Support\Utils::firstSeeDocFor($fixer);

        if (false === filter_var($see, \FILTER_VALIDATE_URL)) {
            return $summary;
        }

        return str_replace($name = "`{$fixer->getDependencyName()}`", "[$name]($see)", $summary);
    }

    private static function diff(string $from, string $to): string
    {
        static $differ;
        $differ ??= new Differ(new StrictUnifiedDiffOutputBuilder([
            'contextLines' => 1024,
            'fromFile' => '',
            'toFile' => '',
        ]));

        $diff = $differ->diff($from, $to);
        $start = strpos($diff, "\n", 10);
        \assert(\is_int($start));

        return (string) substr($diff, $start + 1, -1);
    }
}
