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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern;

trait HasFinalFile
{
    protected string $finalFile;
    protected \SplFileInfo $file;

    public function getFinalFile(): string
    {
        return $this->finalFile;
    }

    public function getFile(): \SplFileInfo
    {
        return $this->file;
    }

    protected function setFinalFile(string $path): void
    {
        $this->finalFile = $path;
    }

    protected function setFile(\SplFileInfo $file): void
    {
        $this->file = $file;
    }
}
