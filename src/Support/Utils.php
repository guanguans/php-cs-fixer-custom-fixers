<?php

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
 * @method void configureIO(InputInterface $input, OutputInterface $output)
 */
final class Utils
{
    /**
     * @see \PhpCsFixer\Hasher
     * @see \PhpCsFixer\Utils
     */
    private function __construct()
    {
        // Cannot create instance of utils class.
    }

    public static function isDryRun(): bool
    {
        return self::hasParameterOption(['check', '--dry-run']);
    }

    public static function isDebug(): bool
    {
        return self::hasParameterOption(['-vvv', '--debug', '--xdebug']);
    }

    /**
     * @param list<string>|string $values
     */
    public static function hasParameterOption($values, bool $onlyParams = false): bool
    {
        return (new ArgvInput)->hasParameterOption($values, $onlyParams);
    }

    /**
     * @return list<string>
     */
    public static function argv(): array
    {
        return $_SERVER['argv'] ??= [];
    }

    /**
     * @see \Rector\Console\Style\SymfonyStyleFactory
     */
    public static function makeSymfonyStyle(?InputInterface $input = null, ?OutputInterface $output = null): SymfonyStyle
    {
        static $symfonyStyle;

        if (
            $symfonyStyle instanceof SymfonyStyle
            && !$input instanceof InputInterface
            && !$output instanceof OutputInterface
        ) {
            return $symfonyStyle;
        }

        $input ??= new ArgvInput;
        $output ??= new ConsoleOutput;

        // to configure all -v, -vv, -vvv options without memory-lock to Application run() arguments
        (fn () => $this->configureIO($input, $output))->call(new Application);

        // --debug or --xdebug is called
        if ($input->hasParameterOption(['--debug', '--xdebug'])) {
            $output->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
        }

        return $symfonyStyle = new SymfonyStyle($input, $output);
    }

    /**
     * @param mixed $value
     *
     * @throws \JsonException
     *
     * @see \Composer\IO\BaseIO::log()
     * @see \PhpCsFixer\Utils::toString()
     */
    public static function toString($value): string
    {
        return \is_string($value)
            ? $value
            : json_encode(
                $value,
                \JSON_FORCE_OBJECT |
                \JSON_INVALID_UTF8_IGNORE |
                \JSON_PRETTY_PRINT |
                \JSON_THROW_ON_ERROR |
                \JSON_UNESCAPED_SLASHES |
                \JSON_UNESCAPED_UNICODE
            );
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
    public static function docFirstSeeFor($objectOrClass): ?string
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
