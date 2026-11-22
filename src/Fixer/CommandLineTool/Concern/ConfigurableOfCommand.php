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

/**
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer
 */
trait ConfigurableOfCommand
{
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
}
