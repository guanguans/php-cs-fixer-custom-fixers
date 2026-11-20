<?php

/** @noinspection EfferentObjectCouplingInspection */
/** @noinspection PhpDeprecationInspection */
/** @noinspection PhpInternalEntityUsedInspection */
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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool;

use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyNameContract;
use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractConfigurableFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern\PostTemporaryMirrorFilePathCommand;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\AllowRisky;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\DependencyName;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\HighestPriority;
use Guanguans\PhpCsFixerCustomFixers\Support\Utils as PhpCsFixerCustomFixersUtils;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;
use PhpCsFixer\FixerDefinition\FileSpecificCodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Utils;
use PhpCsFixer\WhitespacesFixerConfig;

/**
 * @property array{
 *     configurations: list<array<string, mixed>>,
 * } $configuration
 */
final class AggregateCommandLineToolFixer extends AbstractConfigurableFixer
{
    use AllowRisky;
    use HighestPriority;

    /** @api */
    public const CONFIGURATIONS = 'configurations';

    /** @api */
    public const SHORT_NAME = 'short_name';

    /** @api */
    public const IS_POST_TEMPORARY_MIRROR_FILE_PATH_COMMAND = 'is_post_temporary_mirror_file_path_command';

    /** @var non-empty-array<string, \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer> */
    private array $commandLineToolFixers;

    /**
     * @param \PhpCsFixer\Tokenizer\Tokens<\PhpCsFixer\Tokenizer\Token> $tokens
     */
    public function isCandidate(Tokens $tokens): bool
    {
        foreach ($this->getCommandLineToolFixers() as $fixer) {
            if ($fixer->isCandidate($tokens)) {
                return true;
            }
        }

        return false;
    }

    // public function isRisky(): bool
    // {
    //     foreach ($this->getCommandLineToolFixers() as $fixer) {
    //         if ($fixer->isRisky()) {
    //             return true;
    //         }
    //     }
    //
    //     return false;
    // }

    public function getDefinition(): FixerDefinitionInterface
    {
        // [
        //     AutocorrectFixer::class,
        //     LintMdFixer::class,
        //     ShfmtFixer::class,
        //     TyposFixer::class,
        //     XmllintFixer::class,
        //     YamlfmtFixer::class,
        //     ZhlintFixer::class,
        // ];

        return new FixerDefinition(
            $summary = 'Format `any` files.',
            [
                new FileSpecificCodeSample(
                    <<<'DOCKERFILE_WRAP'
                        RUN	foo \
                            # comment 1
                        && \
                        # comment 2
                        bar && \
                        # comment 3
                        baz

                        DOCKERFILE_WRAP,
                    new \SplFileInfo('code-sample.Dockerfile'),
                    $configuration = [
                        self::CONFIGURATIONS => [
                            [
                                /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DockerfmtFixer */
                                AbstractCommandLineToolFixer::COMMAND => ['dockerfmt'],
                                AbstractCommandLineToolFixer::OPTIONS => ['--newline' => true, '--space-redirects' => true, '--write' => true],
                                AbstractInlineHtmlFixer::EXTENSIONS => ['Dockerfile'],
                            ],
                            [
                                /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DotenvLinterFixer */
                                AbstractCommandLineToolFixer::COMMAND => ['dotenv-linter', 'fix'],
                                // AbstractCommandLineToolFixer::OPTIONS => [],
                                AbstractInlineHtmlFixer::EXTENSIONS => ['env', 'env.example'],
                            ],
                            [
                                /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ShfmtFixer */
                                AbstractCommandLineToolFixer::COMMAND => ['shfmt'],
                                AbstractCommandLineToolFixer::OPTIONS => ['--write' => true],
                                AbstractInlineHtmlFixer::EXTENSIONS => ['bats', 'sh'],
                                self::IS_POST_TEMPORARY_MIRROR_FILE_PATH_COMMAND => true,
                            ],
                            [
                                /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\TyposFixer */
                                AbstractCommandLineToolFixer::COMMAND => ['typos'],
                                AbstractCommandLineToolFixer::OPTIONS => [
                                    // When using `Process` to run typos,
                                    // it seems that it cannot find the configuration file in the current working directory.
                                    // So we need to specify the configuration file explicitly.
                                    '--config' => fn () => collect(['.typos.toml', '_typos.toml', 'typos.toml'])
                                        ->when(
                                            $this->configuration[AbstractCommandLineToolFixer::CWD],
                                            static fn (Collection $configFiles, string $cwd): Collection => $configFiles->map(
                                                static fn (string $configFile): string => $cwd.\DIRECTORY_SEPARATOR.$configFile
                                            )
                                        )
                                        ->first(
                                            static fn (string $configFile): bool => is_file($configFile),
                                            false
                                        ),
                                    // Dry run mode will create temporary files, so we need to skip filename checks.
                                    // '--no-check-filenames' => Utils::isDryRun(),
                                    '--no-check-filenames' => true,
                                    '--write-changes' => true,
                                ],
                                AbstractInlineHtmlFixer::EXTENSIONS => ['*'],
                            ],
                            [
                                /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ZhlintFixer */
                                AbstractCommandLineToolFixer::COMMAND => ['zhlint'],
                                AbstractCommandLineToolFixer::OPTIONS => ['--fix' => true],
                                AbstractInlineHtmlFixer::EXTENSIONS => ['txt', 'text', 'md', 'markdown'],
                            ],
                        ],
                    ]
                ),
                new FileSpecificCodeSample(
                    <<<'ENV_WRAP'
                        FOO= BAR
                        BAR = FOO

                        ENV_WRAP,
                    new \SplFileInfo('code-sample.env'),
                    $configuration
                ),
                new FileSpecificCodeSample(
                    <<<'SH_WRAP'
                        #!/bin/bash

                        if foo ;   then
                            bar
                        fi

                        for i in 1 2 3; do
                                    bar
                        done

                        SH_WRAP,
                    new \SplFileInfo('code-sample.sh'),
                    $configuration
                ),
                new FileSpecificCodeSample(
                    <<<'TXT_WRAP'
                        nd
                        numer
                        styl

                        TXT_WRAP,
                    new \SplFileInfo('code-sample.txt'),
                    $configuration
                ),
                new FileSpecificCodeSample(
                    <<<'MD_WRAP'
                        3 minute(s) left 中文

                        case-abbr：Pure JavaScript (a.k.a. Vanilla) 中文

                        case-backslash：a \# b 中文\# __中文__ \# 中文 __\#__ __中文__\#中文__\#__

                        case-traditional：a「b『c』d」e 中文

                        mark-raw：a `b` c `d`e`f` g`h`i 中文

                        mark-type：a__[b](x)__c__[ d ](y)__e 中文

                        space-brackets：(x)a(b)c (d )e( f) g ( h ) i（j）k （l） m __( a )__ b( __c__ )d(e) 中文

                        space-punctuation：中文 。 中文(中文)中文。中文 . 中文（中文）中文.

                        space-quotations: a " hello world " b 中文

                        unify-punctuation：中文,中文 （中文） 中文'中文'中文"中文"中文 （中文）（中文）中文 （中文）。

                        MD_WRAP,
                    // new \SplFileInfo('code-sample.md'),
                    // PhpCsFixerCustomFixersUtils::createDummyFixerTemporaryFileInfo($this, null, null, 'md'),
                    new \SplFileInfo(PhpCsFixerCustomFixersUtils::fixturesPath('code-sample.md')),
                    $configuration
                ),
            ],
            $summary,
            'It depends on the configuration.'
        );
    }

    // public function getPriority(): int
    // {
    //     if (\count($this->getCommandLineToolFixers()) > 1) {
    //         throw new \LogicException('You need to override this method to provide the priority of combined fixers.');
    //     }
    //
    //     return reset($this->getCommandLineToolFixers())->getPriority();
    // }

    public function supports(\SplFileInfo $file): bool
    {
        foreach ($this->getCommandLineToolFixers() as $fixer) {
            if ($fixer->supports($file)) {
                return true;
            }
        }

        return false;
    }

    // public function setWhitespacesConfig(WhitespacesFixerConfig $config): void
    // {
    //     parent::setWhitespacesConfig($config);
    //
    //     foreach ($this->getCommandLineToolFixers() as $fixer) {
    //         if ($fixer instanceof WhitespacesAwareFixerInterface) {
    //             $fixer->setWhitespacesConfig($config);
    //         }
    //     }
    // }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function makeDummySplFileInfo(): \SplFileInfo
    {
        return PhpCsFixerCustomFixersUtils::createDummyFixerTemporaryFileInfo($this, null, null, 'env');
    }

    /**
     * @see \PhpCsFixer\AbstractProxyFixer::applyFix()
     *
     * @param \PhpCsFixer\Tokenizer\Tokens<\PhpCsFixer\Tokenizer\Token> $tokens
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        foreach ($this->getCommandLineToolFixers() as $fixer) {
            $fixer->fix($file, $tokens);
        }
    }

    /**
     * @see \PhpCsFixer\AbstractProxyFixer::__construct()
     *
     * @return non-empty-array<string, \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer>
     */
    private function getCommandLineToolFixers(): array
    {
        return $this->commandLineToolFixers ??= collect($this->configuration[self::CONFIGURATIONS])
            ->map(function (array $configuration): AbstractCommandLineToolFixer {
                $fixer = $this->makeCommandLineToolFixer();
                $fixer->configure($configuration);
                // dump($fixer->getName());

                return $fixer;
            })
            ->pipe(static fn (Collection $fixers) => collect(Utils::sortFixers($fixers->all())))
            ->mapWithKeys(static fn (AbstractCommandLineToolFixer $fixer): array => [$fixer->getName() => $fixer])
            ->all();
    }

    /**
     * @noinspection SelfClassReferencingInspection
     */
    private function makeCommandLineToolFixer(): AbstractCommandLineToolFixer
    {
        /**
         * @property array{
         *     command: list<string>,
         *     short_name: string,
         *     is_post_temporary_mirror_file_path_command: bool,
         * } $configuration
         */
        return new class($this) extends AbstractCommandLineToolFixer implements DependencyNameContract {
            use DependencyName;
            use PostTemporaryMirrorFilePathCommand{
                PostTemporaryMirrorFilePathCommand::command as private postTemporaryMirrorFilePathCommand;
            }
            // use PreTemporaryMirrorFilePathCommand{
            //     PreTemporaryMirrorFilePathCommand::command as private preTemporaryMirrorFilePathCommand;
            // }

            private AggregateCommandLineToolFixer $aggregateCommandLineToolFixer;

            /**
             * @noinspection PhpMissingParentCallCommonInspection
             */
            public function getName(): string
            {
                return "{$this->aggregateCommandLineToolFixer->getName()}/{$this->getShortName()}";
            }

            public function __construct(AggregateCommandLineToolFixer $aggregateCommandLineToolFixer)
            {
                parent::__construct();
                $this->aggregateCommandLineToolFixer = $aggregateCommandLineToolFixer;
            }

            public function getShortName(): string
            {
                // return $this->configuration[AggregateCommandLineToolFixer::SHORT_NAME];
                return \is_array($this->configuration) ? $this->configuration[AggregateCommandLineToolFixer::SHORT_NAME] : parent::getShortName();
            }

            /**
             * @return list<string>
             */
            protected function defaultExtensions(): array
            {
                return ['null'];
            }

            /**
             * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
             */
            protected function codeSamples(): array
            {
                return []; // @codeCoverageIgnore
            }

            /**
             * @return list<string>
             */
            protected function defaultCommand(): array
            {
                return ['null'];
            }

            /**
             * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
             */
            protected function requiredOptions(): array
            {
                return [];
            }

            protected function command(): array
            {
                return $this->configuration[AggregateCommandLineToolFixer::IS_POST_TEMPORARY_MIRROR_FILE_PATH_COMMAND]
                    ? $this->postTemporaryMirrorFilePathCommand()
                    : parent::command();
            }

            /**
             * @noinspection PhpUnusedPrivateMethodInspection
             */
            private function fixerOptionOfShortName(): FixerOptionInterface
            {
                return (new FixerOptionBuilder(AggregateCommandLineToolFixer::SHORT_NAME, 'The short name of the fixer.'))
                    ->setAllowedTypes(['null', 'string'])
                    ->setDefault(null)
                    ->setNormalizer(
                        /** @param \Symfony\Component\OptionsResolver\OptionsResolver $optionsResolver */
                        static fn (/* OptionsResolver */ \ArrayAccess $optionsResolver, ?string $value): string => (string) Str::of(
                            $value ?? collect($optionsResolver[self::COMMAND])
                                // ->filter(static fn (string $option): bool => is_file($option))
                                // ->map(static fn (string $file): string => pathinfo($file, \PATHINFO_FILENAME))
                                ->implode('_')
                        )
                            ->replace(['-', \DIRECTORY_SEPARATOR], '_')
                            ->snake()
                            ->replaceMatches('/_+/', '_')
                            ->trim('_')
                    )
                    ->getOption();
            }

            /**
             * @noinspection PhpUnusedPrivateMethodInspection
             */
            private function fixerOptionOfIsPostTemporaryMirrorFilePathCommand(): FixerOptionInterface
            {
                return (new FixerOptionBuilder(
                    AggregateCommandLineToolFixer::IS_POST_TEMPORARY_MIRROR_FILE_PATH_COMMAND,
                    'Whether post temporary mirror file path command.'
                ))
                    ->setAllowedTypes(['bool'])
                    ->setDefault(false)
                    ->getOption();
            }
        };
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function fixerOptionOfConfigurations(): FixerOptionInterface
    {
        return (new FixerOptionBuilder(self::CONFIGURATIONS, 'The configurations of the command line tool fixer.'))
            ->setAllowedTypes(['array'])
            // ->setDefault([])
            ->getOption();
    }
}
