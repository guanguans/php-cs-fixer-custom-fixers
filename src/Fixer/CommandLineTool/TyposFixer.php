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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool;

use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyCommandContract;
use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyNameContract;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\DependencyName;
use Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample;
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;

/**
 * @see https://github.com/crate-ci/typos
 */
final class TyposFixer extends AbstractCommandLineToolFixer implements DependencyCommandContract, DependencyNameContract
{
    use DependencyName;

    public function supports(\SplFileInfo $file): bool
    {
        return parent::supports($file) && Utils::isTextFile($file);
    }

    /**
     * @see https://github.com/cargo-bins/cargo-binstall
     * @see https://github.com/crate-ci/gh-install
     *
     * @codeCoverageIgnore
     */
    public function dependencyCommand(): string
    {
        switch (\PHP_OS_FAMILY) {
            case 'Darwin':
                return 'brew install typos-cli';
            case 'Windows':
                return 'cargo install typos-cli --locked';
            case 'Linux':
            default:
                return 'curl -sSfL https://raw.githubusercontent.com/cargo-bins/cargo-binstall/main/install-from-binstall-release.sh | bash && cargo binstall typos-cli';
        }
    }

    /**
     * ```shell
     * typos --type-list
     * ```.
     *
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return ['*'];
    }

    /**
     * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new FileSpecificCodeSample(
                $txt = <<<'TXT_WRAP'
                    nd
                    numer
                    styl

                    TXT_WRAP,
                $this,
            ),
            new FileSpecificCodeSample($txt, $this, [self::OPTIONS => ['--sort' => true]]),
        ];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return [$this->getDependencyName()];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [
            '--write-changes' => true,
            // Dry run mode will create temporary files, so we need to skip filename checks.
            '--no-check-filenames' => Utils::isDryRun(),
            // When using `Process` to run typos,
            // it seems that it cannot find the configuration file in the current working directory.
            // So we need to specify the configuration file explicitly.
            '--config' => collect(['.typos.toml', '_typos.toml', 'typos.toml'])->first(
                static fn (string $configFile): bool => is_file($configFile),
                false
            ),
        ];
    }
}
