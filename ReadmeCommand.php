<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\XmlLintFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\InlineHtml\DoctrineSqlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\InlineHtml\PhpMyAdminSqlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixers;
use Nette\Utils\FileSystem;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerDefinition\CodeSampleInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet\RuleSet;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Utils;
use PhpCsFixer\WhitespacesFixerConfig;
use PhpCsFixerCustomFixers\Fixer\AbstractFixer;
use PhpCsFixerCustomFixers\Fixer\DataProviderStaticFixer;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\StrictUnifiedDiffOutputBuilder;

require __DIR__.'/vendor/autoload.php';
\ReadmeCommand::execute();

final class ReadmeCommand
{
    private const README_PLACEHOLDER_START = '<!-- ruledoc-start -->';
    private const README_PLACEHOLDER_END = '<!-- ruledoc-end -->';

    public static function execute(): void
    {
        $_SERVER['argv'] ??= [];

        if (!\in_array('--dry-run', $_SERVER['argv'], true)) {
            $_SERVER['argv'][] = '--dry-run';
        }

        self::renderToReadme(str_replace('<pre>', htmlspecialchars('<pre>'), self::fixers()));
    }

    private static function renderToReadme(string $markdownFileContent): void
    {
        $readmeFilepath = getcwd().'/README.md';
        $readmeContents = FileSystem::read($readmeFilepath);

        /** @var string $readmeContents */
        $readmeContents = preg_replace(
            '#'.preg_quote(self::README_PLACEHOLDER_START, '#').'(.*?)'.
            preg_quote(self::README_PLACEHOLDER_END, '#').'#s',
            self::README_PLACEHOLDER_START.\PHP_EOL.$markdownFileContent.\PHP_EOL.self::README_PLACEHOLDER_END,
            $readmeContents
        );

        FileSystem::write($readmeFilepath, $readmeContents);
    }

    private static function fixers(): string
    {
        $output = '';

        /** @var \PhpCsFixerCustomFixers\Fixer\AbstractFixer $fixer */
        foreach (new Fixers as $fixer) {
            if (
                !$fixer instanceof DoctrineSqlFixer
                && !$fixer instanceof PhpMyAdminSqlFixer
                && !$fixer instanceof XmlLintFixer
            ) {
                continue;
            }

            if ($fixer instanceof WhitespacesAwareFixerInterface) {
                $fixer->setWhitespacesConfig(new WhitespacesFixerConfig);
            }

            $reflectionClass = new \ReflectionClass($fixer);

            $output .= \sprintf(
                "\n#### %s\n%s",
                $reflectionClass->getShortName(),
                $fixer->getDefinition()->getSummary(),
            );

            if ($fixer instanceof DeprecatedFixerInterface) {
                $fixers = (new FixerFactory)
                    ->registerBuiltInFixers()
                    ->registerCustomFixers(new Fixers)
                    ->useRuleSet(new RuleSet(array_combine($fixer->getSuccessorsNames(), [true])))
                    ->getFixers();

                $successors = array_map(
                    static fn (FixerInterface $fixer): string => $fixer instanceof AbstractFixer
                        ? (new \ReflectionObject($fixer))->getShortName()
                        : $fixer->getName(),
                    $fixers,
                );

                $output .= \sprintf("\n  DEPRECATED: use `%s` instead.", implode('`, `', $successors));
            }

            if ($fixer instanceof DataProviderStaticFixer) {
                $fixer->configure(['force' => true]);
            }

            if ($fixer->isRisky()) {
                $riskyDescription = $fixer->getDefinition()->getRiskyDescription();
                $starts = [
                    'Risky when' => 'when',
                    'Fixer could be risky if' => 'when',
                ];

                foreach ($starts as $from => $to) {
                    if (str_starts_with($riskyDescription, $from)) {
                        $riskyDescription = $to.substr($riskyDescription, \strlen($from));
                    }
                }

                $output .= \sprintf(
                    "\n  *Risky: %s.*",
                    lcfirst(rtrim($riskyDescription, '.')),
                );
            }

            if ($fixer instanceof DataProviderStaticFixer) {
                $fixer->configure(['force' => false]);
            }

            if ($fixer instanceof ConfigurableFixerInterface) {
                $output .= "\nConfiguration options:";

                foreach ($fixer->getConfigurationDefinition()->getOptions() as $option) {
                    if ($option->getAllowedValues() !== null) {
                        $allowed = array_map(static fn (string $value): string => \sprintf('\'%s\'', $value), $option->getAllowedValues());
                    } else {
                        /** @var list<string> $allowed */
                        $allowed = $option->getAllowedTypes();
                    }

                    $output .= \sprintf(
                        "\n- `%s` (`%s`): %s; defaults to `%s`",
                        $option->getName(),
                        implode('`, `', $allowed),
                        lcfirst(rtrim($option->getDescription(), '.')),
                        Utils::toString($option->getDefault()),
                    );
                }
            }

            $codeSample = $fixer->getDefinition()->getCodeSamples()[0];
            \assert($codeSample instanceof CodeSampleInterface);

            $originalCode = $codeSample->getCode();

            if ($fixer instanceof ConfigurableFixerInterface) {
                $fixer->configure($codeSample->getConfiguration() ?? []);
            }

            $tokens = Tokens::fromCode($originalCode);
            $fixer->fix(self::createSplFileInfoDouble($fixer), $tokens);
            $fixedCode = $tokens->generateCode();

            $output .= \sprintf(
                "\n```diff\n%s\n```\n",
                self::diff($originalCode, $fixedCode),
            );
        }

        return $output;
    }

    private static function diff(string $from, string $to): string
    {
        static $differ;

        if (null === $differ) {
            $differ = new Differ(new StrictUnifiedDiffOutputBuilder([
                'contextLines' => 1024,
                'fromFile' => '',
                'toFile' => '',
            ]));
        }

        $diff = $differ->diff($from, $to);

        if (!str_contains($diff, "\n")) {
            return $diff;
        }

        $start = strpos($diff, "\n", 10);
        \assert(\is_int($start));

        return substr($diff, $start + 1, -1);
    }

    private static function createSplFileInfoDouble(\Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer $fixer): \SplFileInfo
    {
        return new class(getcwd().\DIRECTORY_SEPARATOR.'file.'.(fn () => $this->defaultExtensions()[0])->call($fixer)) extends \SplFileInfo {
            public function __construct(string $filename)
            {
                parent::__construct($filename);
            }

            public function getRealPath(): string
            {
                return $this->getPathname();
            }
        };
    }
}
