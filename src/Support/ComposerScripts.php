<?php

/** @noinspection EfferentObjectCouplingInspection */
/** @noinspection PhpDeprecationInspection */
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

use Composer\Script\Event;
use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixers;
use Illuminate\Support\Str;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerDefinition\CodeSampleInterface;
use PhpCsFixer\FixerDefinition\VersionSpecificCodeSampleInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet\RuleSet;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Utils;
use PhpCsFixer\WhitespacesFixerConfig;
use Rector\Config\RectorConfig;
use Rector\DependencyInjection\LazyContainerFactory;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\StrictUnifiedDiffOutputBuilder;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 *
 * @property \Symfony\Component\Console\Output\ConsoleOutput $output
 */
final class ComposerScripts
{
    /**
     * @see https://github.com/symplify/rule-doc-generator/blob/main/src/Command/GenerateCommand.php
     * @see \Composer\Util\Silencer
     *
     * @throws \ReflectionException
     */
    public static function updateFixersDocument(Event $event): int
    {
        self::requireAutoload($event);

        assert_options(\ASSERT_BAIL, 1);
        $fixersDocument = str_replace(
            $searches = ['<pre>', '</pre>'],
            array_map(
                static fn (string $search): string => htmlspecialchars(
                    $search,
                    \ENT_QUOTES | \ENT_SUBSTITUTE | \ENT_HTML401
                ),
                $searches
            ),
            self::fixersDocument()
        );
        $contents = file_get_contents($path = getcwd().\DIRECTORY_SEPARATOR.'README.md');
        $updatedContents = preg_replace(
            '#'.preg_quote($start = '<!-- fixers-document:start -->', '#').'(.*?)'
            .preg_quote($end = '<!-- fixers-document:end -->', '#').'#s',
            $start.\PHP_EOL.$fixersDocument.\PHP_EOL.$end,
            $contents
        );
        \assert(\is_string($updatedContents));
        file_put_contents($path, $updatedContents);

        $event->getIO()->info('No errors');

        return 0;
    }

    public static function makeRectorConfig(): RectorConfig
    {
        static $rectorConfig;

        return $rectorConfig ??= (new LazyContainerFactory)->create();
    }

    private static function requireAutoload(Event $event): void
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir').\DIRECTORY_SEPARATOR.'autoload.php';

        (function (): void {
            $this->output->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
        })->call($event->getIO());
    }

    /**
     * @see https://github.com/kubawerlos/php-cs-fixer-custom-fixers/blob/main/.dev-tools/src/Readme/ReadmeCommand.php
     *
     * @throws \ReflectionException
     */
    private static function fixersDocument(): string
    {
        if (!\in_array('--dry-run', $_SERVER['argv'], true)) {
            $_SERVER['argv'][] = '--dry-run';
        }

        /**
         * @see https://github.com/laravel/facade-documenter/blob/main/facade.php
         *
         * @throws \ReflectionException
         */
        $summarizer = static function (AbstractFixer $fixer): string {
            $summary = $fixer->getDefinition()->getSummary();

            $see = Str::of((new \ReflectionObject($fixer))->getDocComment() ?: '')
                ->explode("\n")
                ->skip(1)
                ->reverse()
                ->skip(1)
                ->reverse()
                ->map(static fn ($line): string => ltrim($line, ' \*'))
                ->filter(static fn (?string $line): bool => str_starts_with($line, '@see '))
                ->map(static fn ($line): string => (string) Str::of($line)->after('@see ')->trim())
                ->values()
                ->first();

            return false === filter_var($see, \FILTER_VALIDATE_URL)
                ? $summary
                : str_replace($aliasName = "`{$fixer->getAliasName()}`", "[$aliasName]($see)", $summary);
        };

        $differ = static function (string $from, string $to): string {
            static $differ;
            $differ ??= new Differ(new StrictUnifiedDiffOutputBuilder([
                'contextLines' => 1024,
                'fromFile' => '',
                'toFile' => '',
            ]));

            $diff = $differ->diff($from, $to);
            $start = strpos($diff, "\n", 10);
            \assert(\is_int($start));

            return (string) substr($diff, $start + 1, -1);
        };

        $output = '';
        $fixers = iterator_to_array(new Fixers);
        usort($fixers, static fn (FixerInterface $a, FixerInterface $b): int => strcmp(\get_class($a), \get_class($b)));

        foreach ($fixers as $fixer) {
            if ($fixer instanceof WhitespacesAwareFixerInterface) {
                $fixer->setWhitespacesConfig(new WhitespacesFixerConfig);
            }

            $output .= \sprintf(
                "\n<details>\n<summary><b>%s</b></summary>\n\n%s",
                (new \ReflectionClass($fixer))->getShortName(),
                $summarizer($fixer),
            );

            if ($fixer instanceof DeprecatedFixerInterface) {
                $successors = array_map(
                    static fn (FixerInterface $fixer): string => $fixer instanceof AbstractFixer
                        ? (new \ReflectionObject($fixer))->getShortName()
                        : $fixer->getName(),
                    (new FixerFactory)
                        ->registerBuiltInFixers()
                        ->registerCustomFixers(new Fixers)
                        ->useRuleSet(new RuleSet(array_combine(
                            $successorsNames = $fixer->getSuccessorsNames(),
                            array_pad([], \count($successorsNames), true)
                        )))
                        ->getFixers(),
                );

                $output .= \sprintf("\n\nDeprecated: use `%s` instead.", implode('`, `', $successors));
            }

            if ($fixer->isRisky()) {
                $riskyDescription = $fixer->getDefinition()->getRiskyDescription();
                $starts = [
                    'Fixer could be risky if' => 'when',
                    'Risky when' => 'when',
                ];

                foreach ($starts as $from => $to) {
                    if (str_starts_with($riskyDescription, $from)) {
                        $riskyDescription = $to.substr($riskyDescription, \strlen($from));
                    }
                }

                $output .= \sprintf(
                    "\n\nRisky: %s.",
                    lcfirst(rtrim($riskyDescription, '.')),
                );
            }

            if ($fixer instanceof ConfigurableFixerInterface) {
                $output .= "\n\nConfiguration options:\n";

                foreach ($fixer->getConfigurationDefinition()->getOptions() as $option) {
                    $allowed = (null !== $option->getAllowedValues())
                        ? array_map(
                            static fn (string $value): string => \sprintf("'%s'", $value),
                            $option->getAllowedValues()
                        )
                        : $option->getAllowedTypes();

                    $output .= \sprintf(
                        "\n- `%s` (`%s`): %s; defaults to `%s`",
                        $option->getName(),
                        implode('`, `', $allowed),
                        lcfirst(rtrim($option->getDescription(), '.')),
                        Utils::toString($option->getDefault()),
                    );
                }
            }

            $diffs = array_reduce(
                $fixer->getDefinition()->getCodeSamples(),
                static function (string $diffs, CodeSampleInterface $codeSample) use ($fixer, $differ): string {
                    if (
                        $codeSample instanceof VersionSpecificCodeSampleInterface
                        && !$codeSample->isSuitableFor(\PHP_VERSION_ID)
                    ) {
                        return $diffs;
                    }

                    if ($fixer instanceof ConfigurableFixerInterface) {
                        $fixer->configure($codeSample->getConfiguration() ?? []);
                    }

                    $code = $codeSample->getCode();
                    $tokens = Tokens::fromCode($code);
                    $fixer->fix(
                        new \SplFileInfo(\sprintf(
                            '%s%sfile.%s',
                            getcwd(),
                            \DIRECTORY_SEPARATOR,
                            method_exists($fixer, 'randomExtension') ? $fixer->randomExtension() : 'php'
                        )),
                        $tokens
                    );
                    $fixedCode = $tokens->generateCode();

                    return $diffs.\sprintf(
                        "\n```diff\n%s\n```\n",
                        $differ($code, $fixedCode),
                    );
                },
                ''
            );

            $output .= $diffs ? \sprintf("\n\n%s\n</details>\n", trim($diffs)) : "\n</details>\n";
        }

        return trim($output);
    }
}
