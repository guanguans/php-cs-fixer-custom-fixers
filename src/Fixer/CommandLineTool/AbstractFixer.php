<?php

/** @noinspection EfferentObjectCouplingInspection */
/** @noinspection PhpInternalEntityUsedInspection */

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

use Guanguans\PhpCsFixerCustomFixers\Exception\InvalidConfigurationException;
use Guanguans\PhpCsFixerCustomFixers\Exception\ProcessFailedException;
use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern\HasFinalFile;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern\PreFinalFileCommand;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\SupportsOfExtensionsOrPathArg;
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;
use PhpCsFixer\FileReader;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\Tokenizer\Tokens;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
 * @property array{
 *     command: list<string>,
 *     options: array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>,
 *     cwd: ?string,
 *     env: array<string, string>,
 *     input: ?string,
 *     timeout: null|float|int,
 *     extensions: list<string>,
 * } $configuration
 */
abstract class AbstractFixer extends AbstractInlineHtmlFixer
{
    use HasFinalFile;
    use PreFinalFileCommand;
    use SupportsOfExtensionsOrPathArg;
    public const COMMAND = 'command';
    public const OPTIONS = 'options';
    public const CWD = 'cwd';
    public const ENV = 'env';
    public const INPUT = 'input';
    public const TIMEOUT = 'timeout';

    public function __destruct()
    {
        if (
            isset($this->finalFile)
            && Utils::isDryRun()
            && !Utils::isSequential()
            && file_exists($this->finalFile)
            && is_file($this->finalFile)
        ) {
            // Utils::deferDelete($this->finalFile);
            unlink($this->finalFile);
        }
    }

    /**
     * @param \PhpCsFixer\Tokenizer\Tokens<\PhpCsFixer\Tokenizer\Token> $tokens
     *
     * @throws \JsonException
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        if ([] === $this->configuration[self::COMMAND]) {
            throw new InvalidConfigurationException(\sprintf(
                'Invalid configuration of command for %s, it must not be empty.',
                $this->getName(),
            ));
        }

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

        /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\PintFixer */
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
            $prefix ?? "{$this->getShortKebabName()}-",
            $extension ?? $this->randomExtension(),
            $deferDelete,
        );
    }

    /**
     * @return list<string>
     */
    abstract protected function defaultCommand(): array;

    /**
     * @return array<string, mixed>
     */
    abstract protected function requiredOptions(): array;

    /**
     * @return list<null|scalar>
     *
     * @see \Symfony\Component\Console\Input\ArrayInput
     */
    protected function flatOptions(): array
    {
        return array_merge(...array_map(
            /**
             * @param mixed $value
             */
            function ($value, string $key): array {
                if (!str_starts_with($key, '-')) {
                    throw new InvalidConfigurationException(\sprintf(
                        "Invalid configuration of options item key [$key] for %s, it must start with [-] or [--].",
                        $this->getName(),
                    ));
                }

                if (null === $value || false === $value) {
                    return [];
                }

                if (true === $value) {
                    return [$key];
                }

                if (\is_array($value)) {
                    return array_merge(...array_map(static fn ($v): array => [$key, $v], $value));
                }

                return [$key, $value];
            },
            /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\MarkdownlintFixer::requiredOptions() */
            $options = $this->options(),
            array_keys($options)
        ));
    }

    /**
     * @param mixed $default
     *
     * @return null|list<null|scalar>|scalar
     */
    protected function option(string $key, $default = null)
    {
        return $this->options()[$key] ?? $default;
    }

    /**
     * @return array<string, null|list<null|scalar>|scalar>
     */
    protected function options(): array
    {
        return array_map(
            /**
             * @param mixed $value
             *
             * @return mixed
             */
            fn ($value) => $this->normalizeOption($value),
            $this->configuration[self::OPTIONS] + $this->requiredOptions()
        );
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    protected function normalizeOption($value)
    {
        if (null === $value || \is_scalar($value)) {
            return $value;
        }

        if (\is_object($value) && method_exists($value, '__toString')) {
            return (string) $value;
        }

        if ($value instanceof \Closure) {
            return $this->normalizeOption($value->call($this, $this));
        }

        if (\is_array($value)) {
            return array_map(fn ($v) => $this->normalizeOption($v), $value);
        }

        throw new InvalidConfigurationException(\sprintf(
            'Invalid configuration of options item type [%s] for %s.',
            \gettype($value),
            $this->getName(),
        ));
    }

    protected function fixedCode(): string
    {
        // return file_get_contents($this->finalFile);
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

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @see \Symfony\Component\Process\Process::__construct()
     *
     * @return list<\PhpCsFixer\FixerConfiguration\FixerOptionInterface>
     */
    private function fixerOptions(): array
    {
        return [
            (new FixerOptionBuilder(self::COMMAND, 'The command to run and its arguments listed as separate entries.'))
                ->setAllowedTypes(['string[]'])
                ->setDefault($this->defaultCommand())
                ->setNormalizer(static fn (OptionsResolver $optionsResolver, array $value): array => array_map(
                    static fn (string $value): string => str_contains($value, \DIRECTORY_SEPARATOR) || \in_array($value, ['fix', 'format'], true)
                        ? $value
                        : (new ExecutableFinder)->find($value, $value),
                    $value,
                ))
                ->getOption(),
            (new FixerOptionBuilder(self::OPTIONS, 'The command options to run listed as separate entries.'))
                ->setAllowedTypes(['array'])
                /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\XmllintFixer::requiredOptions() */
                // ->setDefault($this->requiredOptions())
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
        ];
    }
}
