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

use Guanguans\PhpCsFixerCustomFixers\Contract\DependencyNameContract;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern\PostTemporaryMirrorFilePathCommand;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern\PreTemporaryMirrorFilePathCommand;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\DependencyName;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\IteratorAggregateSelf;
use Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample;
use Illuminate\Support\Str;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;

/**
 * @implements \IteratorAggregate<\Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer>
 *
 * @property array{
 *     command: list<string>,
 *     short_name: string,
 *     is_post_temporary_mirror_file_path_command: bool,
 * } $configuration
 */
final class GenericsFixer extends AbstractCommandLineToolFixer implements \IteratorAggregate, DependencyNameContract
{
    use DependencyName;
    use IteratorAggregateSelf;
    use PostTemporaryMirrorFilePathCommand{
        PostTemporaryMirrorFilePathCommand::command as private postTemporaryMirrorFilePathCommand;
    }
    // use PreTemporaryMirrorFilePathCommand{
    //     PreTemporaryMirrorFilePathCommand::command as private preTemporaryMirrorFilePathCommand;
    // }

    /** @api */
    public const SHORT_NAME = 'short_name';

    /** @api */
    public const IS_POST_TEMPORARY_MIRROR_FILE_PATH_COMMAND = 'is_post_temporary_mirror_file_path_command';

    public function getShortName(): string
    {
        // return $this->configuration[self::SHORT_NAME];
        return \is_array($this->configuration) ? $this->configuration[self::SHORT_NAME] : parent::getShortName();
    }

    /**
     * @return list<string>
     */
    protected function defaultExtensions(): array
    {
        return [];
    }

    /**
     * @return list<\Guanguans\PhpCsFixerCustomFixers\FixerDefinition\FileSpecificCodeSample>
     */
    protected function codeSamples(): array
    {
        return [
            new FileSpecificCodeSample(
                <<<'DOCKERFILE_WRAP'
                    RUN	foo \
                        # comment 1
                    && \
                    # comment 2
                    bar && \
                    # comment 3
                    baz

                    DOCKERFILE_WRAP,
                $this,
                [
                    /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DockerfmtFixer */
                    self::COMMAND => ['dockerfmt'],
                    self::OPTIONS => ['--newline' => true, '--space-redirects' => true, '--write' => true],
                    self::EXTENSIONS => ['Dockerfile'],
                ]
            ),
            new FileSpecificCodeSample(
                <<<'ENV_WRAP'
                    FOO= BAR
                    BAR = FOO

                    ENV_WRAP,
                $this,
                [
                    /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DotenvLinterFixer */
                    self::COMMAND => ['dotenv-linter', 'fix'],
                    // self::OPTIONS => [],
                    self::EXTENSIONS => ['env', 'env.example'],
                ]
            ),
            new FileSpecificCodeSample(
                <<<'SH_WRAP'
                    #!/bin/bash

                    if foo ;   then
                        bar
                    fi

                    for i in 1 2 3; do
                                bar
                    done

                    SH_WRAP,
                $this,
                [
                    /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ShfmtFixer */
                    self::COMMAND => ['shfmt'],
                    self::OPTIONS => ['--write' => true],
                    self::EXTENSIONS => ['bats', 'sh'],
                    self::IS_POST_TEMPORARY_MIRROR_FILE_PATH_COMMAND => true,
                ]
            ),
        ];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return [];
    }

    /**
     * @return array<string, null|(\Closure(self): null|scalar|\Stringable)|(list<null|scalar|\Stringable>)|scalar|\Stringable>
     */
    protected function requiredOptions(): array
    {
        return [];
    }

    protected function command(): array
    {
        return $this->configuration[self::IS_POST_TEMPORARY_MIRROR_FILE_PATH_COMMAND]
            ? $this->postTemporaryMirrorFilePathCommand()
            : parent::command();
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function fixerOptionOfShortName(): FixerOptionInterface
    {
        return (new FixerOptionBuilder(self::SHORT_NAME, 'The short name of the fixer.'))
            ->setAllowedTypes(['null', 'string'])
            ->setDefault(null)
            ->setNormalizer(
                /** @param \Symfony\Component\OptionsResolver\OptionsResolver $optionsResolver */
                static fn (/* OptionsResolver */ \ArrayAccess $optionsResolver, ?string $value): string => (string) Str::of(
                    $value ?? collect($optionsResolver[self::COMMAND])
                        ->filter(static fn (string $option): bool => is_file($option))
                        ->map(static fn (string $file): string => pathinfo($file, \PATHINFO_FILENAME))
                        ->implode('_')
                )
                    ->replace('-', '_')
                    ->replaceMatches('/_+/', '_')
                    ->snake()
                    ->trim('_')
            )
            ->getOption();
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function fixerOptionOfIsPostTemporaryMirrorFilePathCommand(): FixerOptionInterface
    {
        return (new FixerOptionBuilder(
            self::IS_POST_TEMPORARY_MIRROR_FILE_PATH_COMMAND,
            'Whether post temporary mirror file path command.'
        ))
            ->setAllowedTypes(['bool'])
            ->setDefault(false)
            ->getOption();
    }
}
