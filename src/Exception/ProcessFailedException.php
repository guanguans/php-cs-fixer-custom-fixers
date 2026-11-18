<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

namespace Guanguans\PhpCsFixerCustomFixers\Exception;

use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyCommandContract;
use Guanguans\PhpCsFixerCustomFixers\Contract\ThrowableContract;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer;
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException as ProcessProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * @property array{
 *     command: list<string>,
 * } $configuration
 */
final class ProcessFailedException extends ProcessProcessFailedException implements ThrowableContract
{
    public function __construct(Process $process, ?AbstractCommandLineToolFixer $fixer = null)
    {
        parent::__construct($process);

        /**
         * ubuntu: `sh: 1: exec: zhlint: not found`
         * macOS: `sh: line 0: exec: zhlint: not found`
         * windows:
         * ```
         * 'zhlint' is not recognized as an internal or external command,
         * operable program or batch file.
         * ```.
         */
        if (
            $fixer instanceof AbstractCommandLineToolFixer && Str::of($process->getErrorOutput())->contains([
                \sprintf(
                    '%s: not found',
                    $command = Arr::first((fn (): array => $this->configuration[AbstractCommandLineToolFixer::COMMAND])->call($fixer)),
                ),
                "'$command' is not",
            ])
        ) {
            $message = [
                'Warning:',
                '================',
                \sprintf(
                    'The command [%s] for %s is not found, please make sure it is installed and available in your PATH environment variable.',
                    $command,
                    $fixer->getName()
                ),
                \sprintf('You can refer to the link [%s] to install the command line tool.', Utils::firstSeeDocFor($fixer)),
            ];

            if ($fixer instanceof DependencyCommandContract) {
                $message[] = "Or try to run the command [{$fixer->dependencyCommand()}].";
            }

            $this->message .= \PHP_EOL.\PHP_EOL.implode(\PHP_EOL, $message);
        }
    }
}
