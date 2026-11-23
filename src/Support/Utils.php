<?php

/** @noinspection EfferentObjectCouplingInspection */
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

use Ergebnis\License\Holder;
use Ergebnis\License\Range;
use Ergebnis\License\Type\MIT;
use Ergebnis\License\Url;
use Ergebnis\License\Year;
use Guanguans\PhpCsFixerCustomFixers\Exception\RuntimeException;
use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixers;
use Guanguans\PhpCsFixerCustomFixers\Support\SplFileInfo as PhpCsFixerCustomFixersSplFileInfo;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use PhpCsFixer\FileRemoval;
use PhpCsFixer\Finder as PhpCsFixerFinder;
use PhpCsFixer\Preg;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo as FinderSplFileInfo;

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
     * @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern\ConfigurableOfOptions::normalizeOptionValue()
     * @see \PhpCsFixer\Cache\Signature::makeJsonEncodable()
     *
     * @param \Closure(\Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer): mixed $callback
     *
     * @return callable&\JsonSerializable
     */
    public static function lazyConfig(\Closure $callback): \JsonSerializable
    {
        return new class($callback) implements \JsonSerializable {
            private \Closure $callback;

            public function __construct(\Closure $callback)
            {
                $this->callback = $callback;
            }

            /**
             * @return mixed
             */
            public function __invoke(AbstractFixer $fixer)
            {
                return $this->callback->call($fixer, $fixer);
            }

            /**
             * @see \Laravel\SerializableClosure\Serializers\Native
             *
             * @return array<string, string>
             *
             * @noinspection TransitiveDependenciesUsageInspection
             */
            public function jsonSerialize(): array
            {
                // return collect((new \ReflectionObject($refFn = new \ReflectionFunction($this->callback)))->getMethods())
                //     ->filter(
                //         static fn (\ReflectionMethod $refMethod): bool => $refMethod->isPublic()
                //             && 0 === $refMethod->getNumberOfRequiredParameters()
                //             && !\in_array(
                //                 $refMethod->getName(),
                //                 [
                //                     'invoke',
                //                     // 'getClosureCalledClass',
                //                     // 'getClosureScopeClass',
                //                     'getClosure',
                //                     'getClosureThis',
                //                     'isDisabled',
                //                 ],
                //                 true
                //             )
                //     )
                //     // ->map->getName()->dd()
                //     ->mapWithKeys(static function (\ReflectionMethod $refMethod) use ($refFn): array {
                //         /**
                // * @param mixed $value
                //          *
                // * @return null|list<null|scalar>|scalar
                //          */
                //         $normalizer = static function ($value) use (&$normalizer) {
                //             switch (true) {
                //                 case $value instanceof \ReflectionExtension:
                //                 case $value instanceof \ReflectionClass:
                //                     return $value->getName();
                //                 case $value instanceof \ReflectionParameter:
                //                     return (string) $value;
                //                 case $value instanceof \ReflectionType:
                //                     switch (true) {
                //                         case $value instanceof \ReflectionNamedType:
                //                             return $value->getName();
                //                         case $value instanceof \ReflectionUnionType: // @codeCoverageIgnoreStart
                //                             return collect($value->getTypes())
                //                                 ->map(static fn (\ReflectionNamedType $refType): string => $refType->getName())
                //                                 ->implode('|');
                //                         case $value instanceof \ReflectionIntersectionType:
                //                             return collect($value->getTypes())
                //                                 ->map(static fn (\ReflectionNamedType $refType): string => $refType->getName())
                //                                 ->implode('&'); // @codeCoverageIgnoreEnd
                //                     }
                //             }
                //
                //             return \is_array($value) ? array_map($normalizer, $value) : $value;
                //         };
                //
                //         return [$refMethod->getName() => $normalizer($refMethod->invoke($refFn))];
                //     })
                //     ->put('reduced_fn', 'reduced_fn')
                //     // ->dd()
                //     ->jsonSerialize();

                return [
                    'fn_fingerprint' => (string) ($refFn = new \ReflectionFunction($this->callback)),
                    'reduced_fn' => (static function () use ($refFn): string {
                        if ($refFn->isInternal()) {
                            return "[ <internal:{$refFn->getExtensionName()}> function {$refFn->getName()} ]";
                        }

                        \assert(\is_string($refFn->getFileName()));
                        \assert(\is_int($refFn->getStartLine()));
                        \assert(\is_int($refFn->getEndLine()));

                        return (string) collect(\PhpToken::tokenize(\sprintf(
                            '<?php %s',
                            collect(file($refFn->getFileName()))
                                ->slice(
                                    $refFn->getStartLine() - 1,
                                    $refFn->getEndLine() - $refFn->getStartLine() + 1
                                )
                                // ->dd()
                                ->implode('')
                        )))
                            ->reduce(
                                static function (Stringable $reducedFn, \PhpToken $token): Stringable {
                                    static $fnStarted = false;

                                    if (!$fnStarted && $token->is([\T_FN, \T_FUNCTION])) {
                                        $fnStarted = true;
                                    }

                                    if (!$fnStarted || $token->is([\T_COMMENT, \T_DOC_COMMENT, \T_OPEN_TAG])) {
                                        return $reducedFn;
                                    }

                                    return $reducedFn->append($token->is(\T_WHITESPACE) ? ' ' : $token->text);
                                },
                                Str::of(''),
                            )
                            ->replaceMatches('/\s+/', ' ')
                            ->trim();
                    })(),
                ];
            }
        };
    }

    /**
     * @return list<string>
     */
    public static function defaultNonPhpPaths(): array
    {
        return array_keys(iterator_to_array(self::defaultNonPhpFinder()));
    }

    public static function defaultNonPhpFinder(): PhpCsFixerFinder
    {
        return PhpCsFixerFinder::create()
            ->in(getcwd())
            ->exclude([
                'Fixtures/',
                '__snapshots__/',
                'baselines/',
                'snapshots/',
                'vendor-bin/',
            ])
            ->notPath([
                '.chglog/CHANGELOG.tpl.md',
                'CHANGELOG.md',
                'README-zh_CN.md',
                'README.md',
                // 'composer.json',
            ])
            ->name(Fixers::make()->extensionPatterns())
            ->notName([
                /** @see SortListItemOfSameScalarTypeRector */
                '/(?<!\.blade)\.php$/',
                '/\.lock$/',
                '/\-lock\.json$/',
                '/\-overview\.md$/',
                // '/zhlint\-.*\..*$/', // Exclude temporary files created by `zhlint` in the current working directory.
            ])
            ->ignoreDotFiles(false)
            ->ignoreUnreadableDirs(false)
            ->ignoreVCS(true)
            ->ignoreVCSIgnored(true)
            /** @see \Symfony\Component\Finder\Iterator\SortableIterator::__construct() */
            // ->sortByExtension()
            // [ERROR] Argument 1 passed to Guanguans\PhpCsFixerCustomFixers\Support\Utils::
            // Guanguans\PhpCsFixerCustomFixers\Support\{closure}() must be an instance of
            // Symfony\Component\Finder\SplFileInfo, instance of ECSPrefix202611\Symfony\Component\Finder\SplFileInfo given
            ->sort(static fn (\SplFileInfo $a, \SplFileInfo $b): int => strnatcmp($a->getExtension(), $b->getExtension()));
    }

    /**
     * @return list<string>
     */
    public static function defaultPaths(): array
    {
        return array_keys(iterator_to_array(self::defaultFinder()));
    }

    public static function defaultFinder(): PhpCsFixerFinder
    {
        return PhpCsFixerFinder::create()
            ->in(getcwd())
            ->exclude([
                'Fixtures/',
                // 'lang/',
                // 'resources/lang/',
                'vendor-bin/',
            ])
            ->notPath([
                // '/lang\/.*\.php$/',
                // '/resources\/lang\/.*\.php$/',
            ])
            ->notName([
                '/\.blade\.php$/',
            ])
            ->ignoreDotFiles(false)->ignoreUnreadableDirs(false)->ignoreVCS(true)->ignoreVCSIgnored(true)
            ->append(self::defaultRootFiles());
    }

    /**
     * @return list<string>
     */
    public static function defaultRootDirectories(): array
    {
        return array_keys(iterator_to_array(
            Finder::create()->directories()->in(getcwd())->depth(0)->sortByName()
                ->exclude([
                    '.chglog/',
                    '.github/',
                    'baselines/',
                    'bootstrap/',
                    'lang/',
                    'node_modules/',
                    'public/',
                    'resources/',
                    'storage/',
                    'vendor-bin/',
                    'vendor/',
                ])
                ->ignoreDotFiles(false)->ignoreUnreadableDirs(false)->ignoreVCS(true)->ignoreVCSIgnored(true)
        ));
    }

    /**
     * @return list<string>
     */
    public static function defaultRootFiles(): array
    {
        return array_keys(iterator_to_array(
            Finder::create()->files()->in(getcwd())->depth(0)->sortByName()
                ->ignoreDotFiles(false)->ignoreUnreadableDirs(false)->ignoreVCS(true)->ignoreVCSIgnored(true)
                ->filter(static fn (FinderSplFileInfo $file): bool => Str::startsWith(
                    $file->getContents(),
                    ['<?php', '#!/usr/bin/env php'.\PHP_EOL.'<?php']
                ))
        ));
    }

    /**
     * @return array<string, mixed>
     */
    public static function configurationOfHeaderCommentFixer(
        string $urlOrName,
        string $startYear,
        ?string $licenseFile = null,
        ?string $holder = null,
        ?string $timezone = null
    ): array {
        return [
            'comment_type' => 'PHPDoc',
            'header' => self::header($urlOrName, $startYear, $licenseFile, $holder, $timezone),
            'location' => 'after_declare_strict',
            'separate' => 'both',
        ];
    }

    /**
     * @noinspection PhpUnhandledExceptionInspection
     */
    public static function header(
        string $urlOrName,
        string $startYear,
        ?string $licenseFile = null,
        ?string $holder = null,
        ?string $timezone = null
    ): string {
        $mit = MIT::text(
            $licenseFile ?? (getcwd().'/LICENSE'),
            Range::since(Year::fromString($startYear), new \DateTimeZone($timezone ?? 'Asia/Shanghai')),
            Holder::fromString($holder ?? 'guanguans<ityaozm@gmail.com>'),
            Url::fromString(filter_var($urlOrName, \FILTER_VALIDATE_URL) ? $urlOrName : "https://github.com/$urlOrName"),
        );

        $mit->save();

        return trim($mit->header());
    }

    public static function isJsonFile(\SplFileInfo $file): bool
    {
        $content = self::getFileContents($file);

        if (empty($content)) {
            return false;
        }

        return json_validate($content);
    }

    public static function isChineseTextFile(\SplFileInfo $file, int $length = 10240, float $threshold = 0.618 * (0.43 / 0.5)): bool
    {
        $content = self::getFileContents($file, $length);

        if (empty($content)) {
            return false;
        }

        if (Preg::match(
            '/(?:[\xC2-\xDF]|[\xE0-\xEF][\x80-\xBF]?|[\xF0-\xF4][\x80-\xBF]{0,2})$/',
            $content,
            $incompleteUtf8Sequences,
            \PREG_OFFSET_CAPTURE,
        )) {
            $content = (string) substr($content, 0, $incompleteUtf8Sequences[0][1]);
        }

        $hanCharacterCount = Preg::matchAll('/\p{Han}/u', $content/* , $hanCharacters */);
        $allCount = mb_strlen($content);
        \assert(\is_int($allCount));
        // dump($hanCharacters);
        // dump($content, $hanCharacterCount, $allCount, $hanCharacterCount / $allCount, $threshold);

        return 0 < $allCount && $hanCharacterCount / $allCount > $threshold;
    }

    /**
     * @see https://github.com/symfony/mime
     * @see https://github.com/thephpleague/mime-type-detection
     */
    public static function isTextFile(\SplFileInfo $file, int $length = 1024): bool
    {
        $content = self::getFileContents($file, $length);

        if (empty($content)) {
            return false;
        }

        // Fread returns false on failure, so we must check if it is a string.
        // If it contains a NULL byte (ASCII 0), it is usually considered a binary file.
        return !str_contains($content, "\0");
    }

    public static function detectIndentation(string $text): ?string
    {
        $indentationLineCounts = [];

        foreach (Preg::split('/\R/', $text) as $line) {
            if ('' === trim($line)) {
                continue;
            }

            Preg::match('/^[ \t]*/', $line, $matches);
            $leadingWhitespace = $matches[0];

            if ('' === $leadingWhitespace) {
                continue;
            }

            if (str_contains($leadingWhitespace, "\t")) {
                $indentation = "\t";
            } else {
                $spaceCount = \strlen($leadingWhitespace);
                $groupCount = intdiv($spaceCount, 4);
                $remainder = $spaceCount % 4;
                $indentation = str_repeat(' ', 0 === $remainder ? 4 : max($groupCount, $remainder));
            }

            $indentationLineCounts[$indentation] = ($indentationLineCounts[$indentation] ?? 0) + 1;
        }

        if ([] === $indentationLineCounts) {
            return null; // 没有缩进
        }

        arsort($indentationLineCounts);

        return array_key_first($indentationLineCounts);
    }

    // public static function dummyRun(): void
    // {
    //     $_SERVER['argv'] = array_filter(
    //         $_SERVER['argv'],
    //         static fn ($value): bool => !\in_array($value, ['--dry-run', 'check', 'fix'], true),
    //     );
    // }

    // public static function dummyDryRun(): void
    // {
    //     \in_array($arg = 'fix', $_SERVER['argv'], true) or array_splice($_SERVER['argv'], 1, 0, [$arg]);
    //     \in_array($arg = '--dry-run', $_SERVER['argv'], true) or $_SERVER['argv'][] = $arg;
    // }

    // public static function isDryRun(): bool
    // {
    //     return self::isRunningInECS()
    //         ? (\in_array(self::getFirstArgument(), ['check', 'worker'], true) && !self::hasParameterOption('--fix', true)) // @codeCoverageIgnore
    //         : (\in_array(self::getFirstArgument(), ['fix', 'worker'], true) && self::hasParameterOption('--dry-run', true))
    //             || self::getFirstArgument() === 'check';
    // }

    public static function dummyDebug(?string $arg = null): void
    {
        // /** @var array{argv: list<string>} $_SERVER */
        \assert(isset($_SERVER['argv']) && \is_array($_SERVER['argv']));
        \in_array($arg ??= '-vvv', $_SERVER['argv'], true) or $_SERVER['argv'][] = $arg;
    }

    public static function isDebug(): bool
    {
        return self::hasParameterOption(self::isRunningInECS() ? '--debug' : '-vvv', true);
    }

    public static function dummyNotTxtFormat(): void
    {
        \assert(isset($_SERVER['argv']) && \is_array($_SERVER['argv']));
        \in_array($arg = '--format=@auto', $_SERVER['argv'], true) or $_SERVER['argv'][] = $arg;
    }

    /**
     * @see \PhpCsFixer\Console\Application::doRun()
     * @see \PhpCsFixer\Console\Command\FixCommand::execute()
     * @see \Symplify\EasyCodingStandard\Contract\Console\Output\OutputFormatterInterface::getName()
     */
    public static function isNotTxtFormat(): bool
    {
        return self::isRunningInECS()
            ? self::hasParameterOption('--output-format', true) && self::getParameterOption('--output-format', null, true) !== 'console' // @codeCoverageIgnore
            : self::hasParameterOption('--format', true) && self::getParameterOption('--format', null, true) !== 'txt';
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
        return getenv('ENV') === 'testing';
    }

    /**
     * @see vendor/friendsofphp/php-cs-fixer/php-cs-fixer
     * @see vendor/symplify/easy-coding-standard/bin/ecs.php
     */
    public static function isRunningInECS(): bool
    {
        return \defined('__ECS_RUNNING__');
    }

    /**
     * @see \Composer\IO\BaseIO::log()
     * @see \PhpCsFixer\Utils::toString()
     * @see https://github.com/Seldaek/monolog/blob/main/src/Monolog/Utils.php#L16
     *
     * @param mixed $value
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

    public static function fixturesPath(string $path = ''): string
    {
        return implode(\DIRECTORY_SEPARATOR, [getcwd(), 'tests', 'Fixtures', ltrim($path, \DIRECTORY_SEPARATOR)]);
    }

    /**
     * @see https://github.com/bovigo/vfsStream
     * @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern\HasTemporaryMirrorFile::createTemporaryMirrorFile()
     * @see \PhpCsFixer\StdinFileInfo
     * @see \PhpCsFixer\Tests\Test\AbstractFixerTestCase::testFixerDefinitions()
     *
     * @noinspection PhpTooManyParametersInspection
     */
    public static function createDummyFixerTemporaryFileInfo(
        AbstractFixer $fixer,
        ?string $directory = null,
        ?string $prefix = null,
        ?string $extension = null,
        ?string $contents = null,
        bool $deferDelete = true
    ): PhpCsFixerCustomFixersSplFileInfo {
        return self::createTemporaryFileInfo(
            $directory ?? self::fixturesPath(),
            $prefix ?? "dummy-{$fixer->getShortKebabName()}-",
            $extension ?? ($fixer instanceof AbstractInlineHtmlFixer ? $fixer->randomExtension() : null),
            $contents,
            $deferDelete
        );
    }

    public static function createTemporaryFileInfo(
        ?string $directory = null,
        ?string $prefix = null,
        ?string $extension = null,
        ?string $contents = null,
        bool $deferDelete = true
    ): PhpCsFixerCustomFixersSplFileInfo {
        return (new PhpCsFixerCustomFixersSplFileInfo(
            self::createTemporaryFile($directory, $prefix, $extension, $deferDelete)
        ))->when(
            $contents,
            static fn (PhpCsFixerCustomFixersSplFileInfo $file, string $contents): PhpCsFixerCustomFixersSplFileInfo => $file
                ->tap(static fn (PhpCsFixerCustomFixersSplFileInfo $file): int => $file->putContents($contents))
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

        if ($extension && !rename($temporaryFile, $temporaryFile .= ".$extension")) {
            throw new RuntimeException("Failed to rename temporary file [$temporaryFile] with extension [$extension]."); // @codeCoverageIgnore
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
            ->map(static fn (string $line): string => ltrim($line, ' \*'))
            ->filter(static fn (string $line): bool => str_starts_with($line, $tagOfSee))
            ->map(static fn (string $line): string => (string) Str::of($line)->after($tagOfSee)->trim())
            ->first();
    }

    /**
     * @return false|string
     */
    private static function getFileContents(\SplFileInfo $file, ?int $length = null)
    {
        if (!$file->isFile() || !$file->isReadable()) {
            return false;
        }

        // \assert(null === $length || 0 < $length);
        $length ??= $file->getSize();

        if (0 === $length) {
            return '';
        }

        return $file->openFile('rb')->fread($length);
    }
}
