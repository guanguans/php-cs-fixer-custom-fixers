<?php

/** @noinspection EfferentObjectCouplingInspection */
/** @noinspection PhpConstantNamingConventionInspection */
/** @noinspection PhpInternalEntityUsedInspection */
/** @noinspection PhpUnusedAliasInspection */

declare(strict_types=1);

/**
 * Copyright (c) 2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool;

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractConfigurableFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concerns\FinalFileAware;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concerns\PreFinalFileCommand;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concerns\AllowRisky;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concerns\HighestPriority;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concerns\InlineHtmlCandidate;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concerns\SupportsExtensions;
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;
use PhpCsFixer\FileReader;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * @see https://github.com/super-linter/super-linter
 * @see https://marketplace.visualstudio.com/search?term=format&target=VSCode&category=All%20categories&sortBy=Relevance
 * @see https://marketplace.visualstudio.com/search?term=lint&target=VSCode&category=All%20categories&sortBy=Relevance
 * @see https://plugins.jetbrains.com/search?search=format
 * @see https://plugins.jetbrains.com/search?search=lint
 * @see https://prettier.io/docs/plugins
 * @see https://github.com/search?q=eslint-plugin&type=repositories
 * @see https://github.com/biomejs/biome
 * @see https://github.com/oxc-project/oxc
 * @see `brew search format`
 * @see `brew search lint`
 *
 * @property array{command: array, options: array, cwd: ?string, env: array, input: ?string, timeout: ?float} $configuration
 */
abstract class AbstractCommandLineToolFixer extends AbstractConfigurableFixer
{
    use AllowRisky;
    use FinalFileAware;
    use HighestPriority;
    use InlineHtmlCandidate;
    use PreFinalFileCommand;
    use SupportsExtensions;

    /** @var string */
    public const COMMAND = 'command';

    /** @var string */
    public const OPTIONS = 'options';

    /** @var string */
    public const CWD = 'cwd';

    /** @var string */
    public const ENV = 'env';

    /** @var string */
    public const INPUT = 'input';

    /** @var string */
    public const TIMEOUT = 'timeout';

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            $summary = "Format a file by [{$this->getShortHeadlineName()}].",
            [new CodeSample($summary)],
            '',
            ''
        );
    }

    protected function createConfigurationDefinition(): FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver(array_merge($this->defaultFixerOptions(), $this->fixerOptions()));
    }

    /**
     * @return list<\PhpCsFixer\FixerConfiguration\FixerOptionInterface>
     */
    protected function defaultFixerOptions(): array
    {
        return [
            (new FixerOptionBuilder(self::COMMAND, 'The command to run the tool (e.g. `dotenv-linter fix`).'))
                ->setAllowedTypes(['array'])
                ->setDefault($this->defaultCommand())
                ->setNormalizer(static fn (OptionsResolver $optionsResolver, array $value): array => array_map(
                    static fn (string $value): string => str_contains($value, \DIRECTORY_SEPARATOR) || \in_array($value, ['fix', 'format'], true)
                        ? $value
                        : (new ExecutableFinder)->find($value, $value),
                    $value,
                ))
                ->getOption(),
            (new FixerOptionBuilder(self::OPTIONS, 'The options to pass to the tool (e.g. `--fix`).'))
                ->setAllowedTypes(['array'])
                ->setDefault([])
                ->getOption(),
            (new FixerOptionBuilder(self::CWD, 'The working directory or null to use the working dir of the current PHP process.'))
                ->setAllowedTypes(['string', 'null'])
                ->setDefault(null)
                ->getOption(),
            (new FixerOptionBuilder(self::ENV, 'The environment variables or null to use the same environment as the current PHP process.'))
                ->setAllowedTypes(['array'])
                ->setDefault([])
                ->getOption(),
            (new FixerOptionBuilder(self::INPUT, 'The input as stream resource, scalar or \Traversable, or null for no input.'))
                ->setAllowedTypes(['string', 'null'])
                ->setDefault(null)
                ->getOption(),
            (new FixerOptionBuilder(self::TIMEOUT, 'The timeout in seconds or null to disable.'))
                ->setAllowedTypes(['float', 'int', 'null'])
                ->setDefault(10)
                ->getOption(),
            $this->fixerOptionOfExtensions(),
        ];
    }

    /**
     * @return list<\PhpCsFixer\FixerConfiguration\FixerOptionInterface>
     */
    protected function fixerOptions(): array
    {
        return [];
    }

    /**
     * @noinspection PhpUnhandledExceptionInspection
     * @noinspection PhpDocMissingThrowsInspection
     *
     * @param \PhpCsFixer\Tokenizer\Tokens<\PhpCsFixer\Tokenizer\Token> $tokens
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        $this->setFinalFile($this->finalFile($file, $tokens));
        $process = new Process(
            $this->command(),
            $this->configuration[self::CWD],
            $this->configuration[self::ENV],
            $this->configuration[self::INPUT],
            $this->configuration[self::TIMEOUT],
        );
        $process->run();
        $this->debugProcess($process);

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // $tokens[0] = new Token([\TOKEN_PARSE, $this->fixedCode()]);
        $tokens->setCode($this->fixedCode());
    }

    /**
     * @param \PhpCsFixer\Tokenizer\Tokens<\PhpCsFixer\Tokenizer\Token> $tokens
     */
    protected function finalFile(\SplFileInfo $file, Tokens $tokens): string
    {
        $finalFile = (string) $file;

        if (Utils::isDryRun()) {
            file_put_contents($finalFile = $this->createTemporaryFile(), $tokens->generateCode());
        }

        return $finalFile;
    }

    protected function createTemporaryFile(
        ?string $directory = null,
        ?string $prefix = null,
        ?string $extension = null,
        bool $deferDelete = true
    ): string {
        return Utils::createTemporaryFile(
            $directory,
            $prefix ?? "{$this->getShortName()}_",
            $extension ?? $this->configuration[self::EXTENSIONS][array_rand($this->configuration[self::EXTENSIONS])],
            $deferDelete,
        );
    }

    /**
     * @return list<string>
     */
    abstract protected function defaultCommand(): array;

    /**
     * @return array<int|string, null|scalar>
     */
    abstract protected function requiredOptions(): array;

    /**
     * @noinspection NestedTernaryOperatorInspection
     */
    protected function options(): array
    {
        return array_merge(...array_map(
            /**
             * @param mixed $value
             * @param int|string $key
             */
            static fn ($value, $key): array => \is_string($key) && str_starts_with($key, '-')
                ? (
                    \is_array($value)
                        ? array_merge(...array_map(static fn (string $val): array => [$key, $val], $value))
                        : [$key, $value]
                )
                : [$value],
            $options = array_merge($this->requiredOptions(), $this->configuration[self::OPTIONS]),
            array_keys($options)
        ));
    }

    protected function fixedCode(): string
    {
        return FileReader::createSingleton()->read($this->finalFile);
    }

    /**
     * @throws \JsonException
     */
    private function debugProcess(Process $process): void
    {
        if (!($symfonyStyle = Utils::makeSymfonyStyle())->isDebug()) {
            return;
        }

        $symfonyStyle->title("Process debugging information for [{$this->getName()}]");
        $symfonyStyle->warning([
            \sprintf('Command Line: %s', $process->getCommandLine()),
            \sprintf('Exit Code: %s', Utils::toString($process->getExitCode())),
            \sprintf('Exit Code Text: %s', Utils::toString($process->getExitCodeText())),
            \sprintf('Output: %s', $process->getOutput()),
            \sprintf('Error Output: %s', $process->getErrorOutput()),
            \sprintf('Working Directory: %s', Utils::toString($process->getWorkingDirectory())),
            \sprintf('Env: %s', Utils::toString($process->getEnv())),
            \sprintf('Input: %s', Utils::toString($process->getInput())),
            \sprintf('Timeout: %s', Utils::toString($process->getTimeout())),
        ]);
    }
}
