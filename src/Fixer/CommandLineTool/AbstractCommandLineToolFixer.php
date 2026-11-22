<?php

/** @noinspection PhpInternalEntityUsedInspection */
/** @noinspection PhpUnusedAliasInspection */
/** @noinspection SenselessMethodDuplicationInspection */
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

use Guanguans\PhpCsFixerCustomFixers\Exception\ProcessFailedException;
use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern\ConfigurableOfCommand;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern\ConfigurableOfOptions;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern\HasTemporaryMirrorFile;
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use Symfony\Component\Process\Process;

/**
 * @see https://github.com/super-linter/super-linter
 * @see https://marketplace.visualstudio.com/search?term=format&target=VSCode&category=All%20categories&sortBy=Relevance
 * @see https://marketplace.visualstudio.com/search?term=lint&target=VSCode&category=All%20categories&sortBy=Relevance
 * @see https://plugins.jetbrains.com/search?search=format
 * @see https://plugins.jetbrains.com/search?search=lint
 * @see https://prettier.io/docs/plugins
 * @see https://github.com/search?q=eslint-plugin&type=repositories
 * @see https://github.com/dprint/dprint
 * @see https://github.com/biomejs/biome
 * @see https://github.com/oxc-project/oxc
 * @see `brew search format`
 * @see `brew search lint`
 *
 * @property array{
 *     command: list<string>,
 *     options: array<string, null|(callable&\JsonSerializable)|list<null|(callable&\JsonSerializable)|scalar>|scalar>,
 *     cwd: ?string,
 *     env: array<string, string>,
 *     input: ?string,
 *     timeout: null|float|int,
 *     extensions: non-empty-list<string>,
 * } $configuration
 */
abstract class AbstractCommandLineToolFixer extends AbstractInlineHtmlFixer
{
    use ConfigurableOfCommand;
    use ConfigurableOfOptions;
    use HasTemporaryMirrorFile;

    /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern\ConfigurableOfCommand */
    public const COMMAND = 'command';

    /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern\ConfigurableOfOptions */
    public const OPTIONS = 'options';
    public const CWD = 'cwd';
    public const ENV = 'env';

    /** @api */
    public const INPUT = 'input';

    /** @api */
    public const TIMEOUT = 'timeout';

    /**
     * @throws \JsonException
     */
    protected function fixCode(string $code): string
    {
        $this->setTemporaryMirrorFile();

        $process = $this->makeProcess();
        $process->run();
        $this->debugProcess($process);

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process, $this);
        }

        return $this->getTemporaryMirrorFile()->getContents();
    }

    protected function makeProcess(): Process
    {
        return new Process(
            $this->command(),
            $this->configuration[self::CWD],
            $this->configuration[self::ENV],
            $this->configuration[self::INPUT],
            $this->configuration[self::TIMEOUT],
        );
    }

    /**
     * @see \PhpCsFixer\Console\Application::doRun()
     * @see \PhpCsFixer\Console\Command\FixCommand::execute()
     *
     * @throws \JsonException
     */
    private function debugProcess(Process $process): void
    {
        if (!Utils::isDebug() || Utils::isNotTxtFormat()) {
            return;
        }

        $io = Utils::makeSymfonyStyle();
        $process->isSuccessful() or $io = $io->getErrorStyle();

        $io->title("Process debugging information for [{$this->getName()}]");
        $io->warning([
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
     * @see \Symfony\Component\Process\Process::__construct()
     *
     * @return list<\PhpCsFixer\FixerConfiguration\FixerOptionInterface>
     *
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function fixerOptions(): array
    {
        /** @see \PhpCsFixer\FixerConfiguration\FixerOption::unbind() */
        // $name = $this->getName();

        return [
            (new FixerOptionBuilder(self::CWD, 'The working directory or null to use the working dir of the current PHP process.'))
                ->setAllowedTypes(['null', 'string'])
                ->setDefault(null)
                ->getOption(),
            (new FixerOptionBuilder(self::ENV, 'The environment variables or null to use the same environment as the current PHP process.'))
                ->setAllowedTypes(['array'])
                ->setDefault([])
                ->getOption(),
            (new FixerOptionBuilder(self::INPUT, 'The input as stream resource, scalar or \Traversable, or null for no input.'))
                ->setAllowedTypes(['null', 'string'])
                ->setDefault(null)
                ->getOption(),
            (new FixerOptionBuilder(self::TIMEOUT, 'The timeout in seconds or null to disable.'))
                ->setAllowedTypes(['float', 'int', 'null'])
                ->setDefault(10)
                ->getOption(),
        ];
    }
}
