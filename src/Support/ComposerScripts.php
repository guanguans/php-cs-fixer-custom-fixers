<?php

/** @noinspection EfferentObjectCouplingInspection */
/** @noinspection PhpDeprecationInspection */
/** @noinspection PhpInternalEntityUsedInspection */
/** @noinspection PhpUnused */

declare(strict_types=1);

/**
 * Copyright (c) 2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

namespace Guanguans\PhpCsFixerCustomFixers\Support;

use Composer\Script\Event;
use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixers;
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
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 *
 * @property \Symfony\Component\Console\Output\ConsoleOutput $output
 */
final class ComposerScripts
{
    /**
     * @noinspection PhpUnused
     *
     * @return int<0, 0>
     */
    public static function checkDocument(Event $event): int
    {
        self::requireAutoload($event);

        $extensions = collect(Fixers::make()->extensions())
            ->reject(static fn (string $ext): bool => \in_array(
                $ext,
                [
                    'env.example',
                    'markdown',
                    'php',
                    'xml.dist',
                    'yml',
                    'zh_CN.md',
                ],
                true
            ))
            ->sort(static fn (string $a, string $b): int => strcasecmp($a, $b))
            ->all();

        file_put_contents(
            __DIR__.'/../../tests.platforms',
            implode(\PHP_EOL, array_merge(
                $descriptionContents = [implode(',', $extensions), implode('、', $extensions)],
                [
                    $keywordContent = trim(
                        array_reduce(
                            collect(Fixers::make()->getAliasNames())
                                ->reject(static fn (string $aliasName): bool => \in_array(
                                    $aliasName,
                                    [
                                        'aliasName',
                                        'aliasName',
                                    ],
                                    true
                                ))
                                ->map(
                                    static fn (string $aliasName): string => (string) Str::of($aliasName)
                                        ->replace('/', '-')
                                        ->slug()
                                )
                                ->sort(static fn (string $a, string $b): int => strcasecmp($a, $b))
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
        self::requireAutoload($event);

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

    private static function requireAutoload(Event $event): void
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir').\DIRECTORY_SEPARATOR.'autoload.php';

        (function (): void {
            $this->output->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
        })->call($event->getIO());
    }

    /**
     * @see https://github.com/kubawerlos/php-cs-fixer-custom-fixers/blob/main/.dev-tools/src/Readme/ReadmeCommand.php
     *
     * @throws \ReflectionException
     */
    private static function fixersDocument(): string
    {
        return (string) collect(new Fixers)
            ->tap(static function (): void {
                if (!\in_array('--dry-run', $_SERVER['argv'], true)) {
                    $_SERVER['argv'][] = '--dry-run';
                }
            })
            ->sort(static fn (AbstractFixer $a, AbstractFixer $b): int => strcmp(\get_class($a), \get_class($b)))
            ->reduce(
                static fn (Stringable $doc, AbstractFixer $fixer): Stringable => $doc
                    ->tap(static function () use ($fixer): void {
                        if ($fixer instanceof WhitespacesAwareFixerInterface) {
                            $fixer->setWhitespacesConfig(new WhitespacesFixerConfig);
                        }
                    })
                    ->append("\n\n<details>")
                    ->append(
                        \sprintf("\n<summary><b>%s</b></summary>", (new \ReflectionClass($fixer))->getShortName()),
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
                                            ? (new \ReflectionObject($fixer))->getShortName()
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
                                                static fn (string $value): string => "'$value'",
                                                (array) $option->getAllowedValues()
                                            ) ?: $option->getAllowedTypes()
                                        ),
                                        lcfirst(rtrim($option->getDescription(), '.')),
                                        Utils::toString($option->getDefault()),
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
                                        $fixer->fix(
                                            new \SplFileInfo(\sprintf(
                                                '%s%sfile.%s',
                                                getcwd(),
                                                \DIRECTORY_SEPARATOR,
                                                method_exists($fixer, 'randomExtension') ? $fixer->randomExtension() : 'php'
                                            )),
                                            $tokens
                                        );
                                    }
                                );

                                return $doc->append(
                                    \sprintf(
                                        "\n\nSample $index: configuration(`%s`)",
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
            ->replaceMatches(
                [
                    /** @lang PhpRegExp */
                    '|/opt/homebrew/Cellar/php@\d+\.\d+/.*/bin/php|',
                    /** @lang PhpRegExp */
                    '|/opt/homebrew/opt/php@\d+\.\d+/bin/php|',
                ],
                'php',
            )
            ->replace(
                $searches = ['<pre>', '</pre>'],
                array_map(
                    static fn (string $search): string => htmlspecialchars($search, \ENT_QUOTES | \ENT_SUBSTITUTE),
                    $searches
                ),
            );
    }

    /**
     * @see https://github.com/laravel/facade-documenter/blob/main/facade.php
     *
     * @throws \ReflectionException
     */
    private static function summaryFor(AbstractFixer $fixer): string
    {
        $summary = $fixer->getDefinition()->getSummary();

        $see = Str::of((new \ReflectionObject($fixer))->getDocComment() ?: '')
            ->explode("\n")
            ->skip(1)
            ->reverse()
            ->skip(1)
            ->reverse()
            ->map(static fn ($line): string => ltrim($line, ' \*'))
            ->filter(static fn (?string $line): bool => str_starts_with($line, '@see '))
            ->map(static fn ($line): string => (string) Str::of($line)->after('@see ')->trim())
            ->values()
            ->first();

        return false === filter_var($see, \FILTER_VALIDATE_URL)
            ? $summary
            : str_replace($aliasName = "`{$fixer->getAliasName()}`", "[$aliasName]($see)", $summary);
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
