<?php

/** @noinspection PhpClassHasTooManyDeclaredMembersInspection */
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

namespace Guanguans\PhpCsFixerCustomFixers\Support;

use Guanguans\PhpCsFixerCustomFixers\Exception\RuntimeException;
use Illuminate\Support\Str;
use PhpCsFixer\FileRemoval;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @api
 *
 * @method void configureIO(InputInterface $input, OutputInterface $output)
 */
final class Utils
{
    /**
     * @see \PhpCsFixer\Hasher
     * @see \PhpCsFixer\Utils
     */
    private function __construct() {}

    /**
     * @see https://github.com/symfony/mime
     * @see https://github.com/thephpleague/mime-type-detection
     *
     * @noinspection BadExceptionsProcessingInspection
     */
    public static function isTextFile(\SplFileInfo $file): bool
    {
        if (self::isRunningInTesting() && self::isDryRun()) {
            return true;
        }

        if (!$file->isFile() || !$file->isReadable()) {
            return false;
        }

        try {
            // SplFileInfo::openFile throws RuntimeException if the file cannot be opened.
            $content = $file->openFile('rb')->fread(512);
        } catch (\Throwable $throwable) {
            return false;
        }

        // Fread returns false on failure, so we must check if it is a string.
        if (!\is_string($content)) {
            return false;
        }

        // If it contains a NULL byte (ASCII 0), it is usually considered a binary file.
        return false === strpos($content, "\0");
    }

    public static function dummyRun(): void
    {
        $_SERVER['argv'] = array_filter(
            $_SERVER['argv'],
            static fn ($value): bool => !\in_array($value, ['check', 'fix', '--dry-run'], true),
        );
    }

    public static function dummyDryRun(): void
    {
        \in_array($arg = 'fix', $_SERVER['argv'], true) or array_splice($_SERVER['argv'], 1, 0, [$arg]);
        \in_array($arg = '--dry-run', $_SERVER['argv'], true) or $_SERVER['argv'][] = $arg;
    }

    public static function isDryRun(): bool
    {
        // return self::hasParameterOption('check', true)
        //     || (self::hasParameterOption('fix', true) && self::hasParameterOption('--dry-run', true))
        //     || (self::hasParameterOption('worker', true) && self::hasParameterOption('--dry-run', true));
        return 'check' === self::getFirstArgument()
            || ('fix' === self::getFirstArgument() && self::hasParameterOption('--dry-run', true))
            || ('worker' === self::getFirstArgument() && self::hasParameterOption('--dry-run', true));
    }

    public static function dummyDebug(?string $arg = null): void
    {
        // /** @var array{argv: list<string>} $_SERVER */
        \in_array($arg ??= '-vvv', $_SERVER['argv'], true) or $_SERVER['argv'][] = $arg;
    }

    public static function isDebug(): bool
    {
        return self::hasParameterOption('-vvv', true);
    }

    public static function dummyNotTxtFormat(): void
    {
        \in_array($arg = '--format=@auto', $_SERVER['argv'], true) or $_SERVER['argv'][] = $arg;
    }

    /**
     * @see \PhpCsFixer\Console\Application::doRun()
     * @see \PhpCsFixer\Console\Command\FixCommand::execute()
     */
    public static function isNotTxtFormat(): bool
    {
        return self::hasParameterOption('--format', true) && 'txt' !== self::getParameterOption('--format', null, true);
    }

    public static function getFirstArgument(): ?string
    {
        return (new ArgvInput)->getFirstArgument();
    }

    /**
     * @param list<string>|string $values The values to look for in the raw parameters (can be an array)
     * @param bool $onlyParams Only check real parameters, skip those following an end of options (--) signal
     */
    public static function hasParameterOption($values, bool $onlyParams = false): bool
    {
        return (new ArgvInput)->hasParameterOption($values, $onlyParams);
    }

    /**
     * @param list<string>|string $values The value(s) to look for in the raw parameters (can be an array)
     * @param null|array<array-key, mixed>|bool|float|int|string $default The default value to return if no result is found
     * @param bool $onlyParams Only check real parameters, skip those following an end of options (--) signal
     *
     * @return mixed
     */
    public static function getParameterOption($values, $default = false, bool $onlyParams = false)
    {
        return (new ArgvInput)->getParameterOption($values, $default, $onlyParams);
    }

    /**
     * @see \Rector\Console\Style\SymfonyStyleFactory
     */
    public static function makeSymfonyStyle(?InputInterface $input = null, ?OutputInterface $output = null): SymfonyStyle
    {
        static $symfonyStyle;

        if (
            $symfonyStyle instanceof SymfonyStyle
            && (
                !$input instanceof InputInterface
                || (string) \Closure::bind(
                    static fn (SymfonyStyle $symfonyStyle): InputInterface => $symfonyStyle->input,
                    null,
                    SymfonyStyle::class
                )($symfonyStyle) === (string) $input
            )
            && (
                !$output instanceof OutputInterface
                || \Closure::bind(
                    static fn (SymfonyStyle $symfonyStyle): OutputInterface => $symfonyStyle->output,
                    null,
                    SymfonyStyle::class
                )($symfonyStyle) === $output
            )
        ) {
            return $symfonyStyle;
        }

        $input ??= new ArgvInput;
        $output ??= new ConsoleOutput;

        // to configure all -v, -vv, -vvv options without memory-lock to Application run() arguments
        (fn () => $this->configureIO($input, $output))->call(new Application);

        // --debug or --xdebug is called
        if ($input->hasParameterOption(['--debug', '--xdebug'], true)) {
            $output->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
        }

        // disable output for testing
        if (self::isRunningInTesting()) {
            $output->setVerbosity(OutputInterface::VERBOSITY_QUIET);
        }

        return $symfonyStyle = new SymfonyStyle($input, $output);
    }

    public static function isRunningInTesting(): bool
    {
        return 'testing' === getenv('ENV');
    }

    /**
     * @see \Composer\IO\BaseIO::log()
     * @see \PhpCsFixer\Utils::toString()
     * @see https://github.com/Seldaek/monolog/blob/main/src/Monolog/Utils.php#L16
     *
     * @param mixed $value
     *
     * @throws \JsonException
     */
    public static function toString($value, bool $jsonForceObject = true): string
    {
        $flags =
            \JSON_INVALID_UTF8_IGNORE |
            \JSON_INVALID_UTF8_SUBSTITUTE |
            \JSON_PARTIAL_OUTPUT_ON_ERROR |
            \JSON_PRESERVE_ZERO_FRACTION |
            \JSON_PRETTY_PRINT |
            \JSON_THROW_ON_ERROR |
            \JSON_UNESCAPED_SLASHES |
            \JSON_UNESCAPED_UNICODE;

        $jsonForceObject and $flags |= \JSON_FORCE_OBJECT;

        return \is_string($value) ? $value : json_encode($value, $flags);
    }

    /**
     * @see \Illuminate\Filesystem\Filesystem::ensureDirectoryExists()
     * @see \Psl\Filesystem\create_temporary_file()
     * @see \Spatie\TemporaryDirectory\TemporaryDirectory
     *
     * @noinspection PhpUndefinedNamespaceInspection
     */
    public static function createTemporaryFile(
        ?string $directory = null,
        ?string $prefix = null,
        ?string $extension = null,
        bool $deferDelete = true
    ): string {
        $directory ??= sys_get_temp_dir();

        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException("The directory [$directory] could not be created."); // @codeCoverageIgnore
        }

        $temporaryFile = tempnam($directory, $prefix ?? '');

        if (!$temporaryFile) {
            throw new RuntimeException("Failed to create a temporary file in directory [$directory]."); // @codeCoverageIgnore
        }

        if ($extension) {
            $isRenamed = rename($temporaryFile, $temporaryFile .= ".$extension");

            if (!$isRenamed) {
                throw new RuntimeException("Failed to rename temporary file [$temporaryFile] with extension [$extension]."); // @codeCoverageIgnore
            }
        }

        $deferDelete and self::deferDelete($temporaryFile);

        return $temporaryFile;
    }

    /**
     * @see \Illuminate\Filesystem\Filesystem::delete()
     * @see \PhpCsFixer\FileRemoval::__construct()
     * @see \Symfony\Component\Process\PhpProcess::__construct()
     * @see \Symfony\Component\Process\PhpSubprocess::__construct()
     *
     * @noinspection PhpUndefinedNamespaceInspection
     */
    public static function deferDelete(string ...$paths): void
    {
        foreach ($paths as $path) {
            // register_shutdown_function('unlink', $path);
            ($fileRemoval ??= new FileRemoval)->observe($path);
        }
    }

    /**
     * @see https://github.com/laravel/facade-documenter/blob/main/facade.php
     *
     * @param class-string|object $objectOrClass
     */
    public static function firstSeeDocFor($objectOrClass): ?string
    {
        $tagOfSee = '@see ';

        return Str::of((new \ReflectionClass($objectOrClass))->getDocComment() ?: '')
            ->explode("\n")
            ->skip(1)
            ->reverse()
            ->skip(1)
            ->reverse()
            ->map(static fn ($line): string => ltrim($line, ' \*'))
            ->filter(static fn (?string $line): bool => str_starts_with($line, $tagOfSee))
            ->map(static fn ($line): string => (string) Str::of($line)->after($tagOfSee)->trim())
            ->values()
            ->first();
    }
}
