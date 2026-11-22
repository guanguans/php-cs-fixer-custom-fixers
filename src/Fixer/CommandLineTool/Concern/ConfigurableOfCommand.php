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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern;

use Guanguans\PhpCsFixerCustomFixers\Exception\MissingOptionsException;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;
use Symfony\Component\Process\ExecutableFinder;

/**
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\Configurable
 *
 * @property array{
 *     command: list<string>,
 * } $configuration
 */
trait ConfigurableOfCommand
{
    // /** @var string */
    // public const COMMAND = 'command';
    protected bool $isPostTemporaryMirrorFilePathCommand = false;

    /**
     * @return list<null|scalar>
     */
    protected function command(): array
    {
        return $this->isPostTemporaryMirrorFilePathCommand
            ? $this->postTemporaryMirrorFilePathCommand()
            : $this->preTemporaryMirrorFilePathCommand();
    }

    /**
     * @return list<string>
     */
    abstract protected function defaultCommand(): array;

    /**
     * @return list<null|scalar>
     */
    private function preTemporaryMirrorFilePathCommand(): array
    {
        return array_merge(
            $this->configuration[self::COMMAND],
            [$this->getTemporaryMirrorFilePath()],
            $this->normalizedOptions()
        );
    }

    /**
     * @return list<null|scalar>
     */
    private function postTemporaryMirrorFilePathCommand(): array
    {
        return array_merge(
            $this->configuration[self::COMMAND],
            $this->normalizedOptions(),
            [$this->getTemporaryMirrorFilePath()]
        );
    }

    /**
     * @see \Symfony\Component\Process\Process::__construct()
     *
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function fixerOptionOfCommand(): FixerOptionInterface
    {
        /** @see \PhpCsFixer\FixerConfiguration\FixerOption::unbind() */
        // $name = $this->getName();

        return (new FixerOptionBuilder(self::COMMAND, 'The command to run and its arguments listed as separate entries.'))
            ->setAllowedTypes(['string[]'])
            ->setDefault($this->defaultCommand())
            // Argument 1 passed to Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer
            // ::Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\{closure}() must be an instance of
            // Symfony\Component\OptionsResolver\OptionsResolver,
            // instance of ECSPrefix202611\Symfony\Component\OptionsResolver\OptionsResolver given
            ->setNormalizer(static function (/* OptionsResolver */ \ArrayAccess $_, array $value): array {
                /** @var list<string> $value */
                if ([] === $value) {
                    // throw new RequiredFixerConfigurationException(
                    //     $name,
                    //     'Invalid configuration for command, it must not be empty.'
                    // );
                    throw new MissingOptionsException('Invalid configuration for command, it must not be empty.');
                }

                return array_map(
                    static fn (string $v): string => str_contains($v, \DIRECTORY_SEPARATOR) || \in_array($v, ['fix', 'format'], true)
                        ? $v
                        : (string) (new ExecutableFinder)->find($v, $v),
                    $value,
                );
            })
            ->getOption();
    }
}
