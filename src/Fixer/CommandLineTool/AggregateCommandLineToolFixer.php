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
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\AllowRisky;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\DependencyName;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\HighestPriority;
use Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample;
use Guanguans\PhpCsFixerCustomFixers\Support\Utils as PhpCsFixerCustomFixersUtils;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Utils;
use PhpCsFixer\WhitespacesFixerConfig;

/**
 * @property array{
 *     configurations: non-empty-list<array<string, mixed>>,
 * } $configuration
 *
 * @method list<string> defaultCommand()
 * @method list<string> defaultExtensions()
 * @method array<string, mixed> requiredOptions()
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

    /** @api */
    public const IS_TEMPORARY_MIRROR_FILE_CREATED_IN_ORIGINAL_DIRECTORY = 'is_temporary_mirror_file_created_in_original_directory';

    /** @var non-empty-array<string, \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer> */
    private array $commandLineToolFixers;

    /**
     * @return array{configurations: non-empty-list<array<string, mixed>>}
     */
    public static function configurationSample(): array
    {
        return [
            self::CONFIGURATIONS => [
                [
                    AbstractCommandLineToolFixer::COMMAND => (fn (): array => $this->defaultCommand())->call(ShfmtFixer::make()),
                    AbstractCommandLineToolFixer::OPTIONS => (fn (): array => $this->requiredOptions())->call(ShfmtFixer::make()),
                    AbstractInlineHtmlFixer::EXTENSIONS => (fn (): array => $this->defaultExtensions())->call(ShfmtFixer::make()),
                    self::IS_POST_TEMPORARY_MIRROR_FILE_PATH_COMMAND => true,
                ],
                [
                    AbstractCommandLineToolFixer::COMMAND => (fn (): array => $this->defaultCommand())->call(TyposFixer::make()),
                    AbstractCommandLineToolFixer::OPTIONS => (fn (): array => $this->requiredOptions())->call(TyposFixer::make()),
                    AbstractInlineHtmlFixer::EXTENSIONS => (fn (): array => $this->defaultExtensions())->call(TyposFixer::make()),
                ],
                [
                    AbstractCommandLineToolFixer::COMMAND => (fn (): array => $this->defaultCommand())->call(ZhlintFixer::make()),
                    AbstractCommandLineToolFixer::OPTIONS => (fn (): array => $this->requiredOptions())->call(ZhlintFixer::make()),
                    AbstractInlineHtmlFixer::EXTENSIONS => (fn (): array => $this->defaultExtensions())->call(ZhlintFixer::make()),
                    self::IS_TEMPORARY_MIRROR_FILE_CREATED_IN_ORIGINAL_DIRECTORY => true,
                ],
            ],
        ];
    }

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
        return new FixerDefinition(
            $summary = 'Format `any` files.',
            [
                new FileSpecificCodeSample(
                    ShfmtFixer::make()->getDefinition()->getCodeSamples()[0]->getCode(),
                    ShfmtFixer::make(),
                    self::configurationSample()
                ),
                new FileSpecificCodeSample(
                    TyposFixer::make()->getDefinition()->getCodeSamples()[0]->getCode(),
                    TyposFixer::make(),
                    self::configurationSample()
                ),
                new FileSpecificCodeSample(
                    ZhlintFixer::make()->getDefinition()->getCodeSamples()[0]->getCode(),
                    ZhlintFixer::make(),
                    self::configurationSample()
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

    // /**
    //  * @noinspection PhpMissingParentCallCommonInspection
    //  */
    // public function makeDummySplFileInfo(): \SplFileInfo
    // {
    //     return PhpCsFixerCustomFixersUtils::createDummyFixerTemporaryFileInfo($this);
    // }

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
         *     is_temporary_mirror_file_created_in_original_directory: bool,
         * } $configuration
         */
        return new class($this) extends AbstractCommandLineToolFixer implements DependencyNameContract {
            use DependencyName;
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
                return \is_array($this->configuration)
                    ? $this->configuration[AggregateCommandLineToolFixer::SHORT_NAME]
                    : parent::getShortName();
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
                $fixer = $this;

                return (new FixerOptionBuilder(
                    AggregateCommandLineToolFixer::IS_POST_TEMPORARY_MIRROR_FILE_PATH_COMMAND,
                    'Whether post temporary mirror file path command.'
                ))
                    ->setAllowedTypes(['bool'])
                    ->setDefault(false)
                    ->setNormalizer(
                        /** @param \Symfony\Component\OptionsResolver\OptionsResolver $_ */
                        static fn (/* OptionsResolver */ \ArrayAccess $_, bool $value): bool => $fixer
                            ->isPostTemporaryMirrorFilePathCommand = $value
                    )
                    ->getOption();
            }

            /**
             * @noinspection PhpUnusedPrivateMethodInspection
             */
            private function fixerOptionOfIsTemporaryMirrorFileCreatedInOriginalDirectory(): FixerOptionInterface
            {
                $fixer = $this;

                return (new FixerOptionBuilder(
                    AggregateCommandLineToolFixer::IS_TEMPORARY_MIRROR_FILE_CREATED_IN_ORIGINAL_DIRECTORY,
                    'Whether temporary mirror file created in original directory.'
                ))
                    ->setAllowedTypes(['bool'])
                    ->setDefault(false)
                    ->setNormalizer(
                        /** @param \Symfony\Component\OptionsResolver\OptionsResolver $_ */
                        static fn (/* OptionsResolver */ \ArrayAccess $_, bool $value): bool => $fixer
                            ->isTemporaryMirrorFileCreatedInOriginalDirectory = $value
                    )
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
            ->setAllowedTypes(['array[]'])
            // ->setDefault([])
            ->getOption();
    }
}
