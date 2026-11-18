<?php

/** @noinspection EfferentObjectCouplingInspection */
/** @noinspection PhpDeprecationInspection */
/** @noinspection PhpInternalEntityUsedInspection */
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
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern\PostTemporaryMirrorFilePathCommand;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\DependencyName;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\HighestPriority;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\IteratorAggregateSelf;
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
 * @implements \IteratorAggregate<\Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer>
 *
 * @property array{
 *     configurations: list<array<string, mixed>>,
 * } $configuration
 */
final class AggregateCommandLineToolFixer extends AbstractConfigurableFixer implements \IteratorAggregate
{
    use HighestPriority;
    use IteratorAggregateSelf;

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

    public function isRisky(): bool
    {
        foreach ($this->getCommandLineToolFixers() as $fixer) {
            if ($fixer->isRisky()) {
                return true;
            }
        }

        return false;
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            $summary = 'Format `any` files.',
            [],
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

    public function setWhitespacesConfig(WhitespacesFixerConfig $config): void
    {
        parent::setWhitespacesConfig($config);

        foreach ($this->getCommandLineToolFixers() as $fixer) {
            if ($fixer instanceof WhitespacesAwareFixerInterface) {
                $fixer->setWhitespacesConfig($config);
            }
        }
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
                return [];
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
                    ->setAllowedTypes(['string', 'null'])
                    ->setDefault(null)
                    ->setNormalizer(
                        /** @param \Symfony\Component\OptionsResolver\OptionsResolver $optionsResolver */
                        static fn (/* OptionsResolver */ \ArrayAccess $optionsResolver, ?string $value): string => (string) Str::of(
                            $value ?? collect($optionsResolver[self::COMMAND])
                                // ->filter(static fn (string $option): bool => is_file($option))
                                // ->map(static fn (string $file): string => pathinfo($file, \PATHINFO_FILENAME))
                                ->implode('_')
                        )
                            ->replace('-', '_')
                            ->replaceMatches('/_+/', '_')
                            ->snake()
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
            ->getOption();
    }
}
