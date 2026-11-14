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

use Guanguans\PhpCsFixerCustomFixers\Support\SplFileInfo;
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

trait HasTemporaryMirrorFile
{
    protected bool $isTemporaryMirrorFileCreatedInOriginalDirectory = false;
    private SplFileInfo $temporaryMirrorFile;

    public function getTemporaryMirrorFilePathname(): string
    {
        $cmd = Str::finish($this->configuration[self::CWD] ?? getcwd(), \DIRECTORY_SEPARATOR);

        return (string) Str::of($this->getTemporaryMirrorFile())
            // ->chopStart($cmd)
            // ->replaceStart($cmd, '')
            ->whenStartsWith($cmd, static fn (Stringable $pathname): Stringable => $pathname->replaceFirst($cmd, ''));
    }

    public function getTemporaryMirrorFile(): SplFileInfo
    {
        return $this->temporaryMirrorFile;
    }

    protected function setTemporaryMirrorFile(?SplFileInfo $temporaryMirrorFile = null): void
    {
        $this->temporaryMirrorFile = $temporaryMirrorFile ?? $this->createTemporaryMirrorFile();
    }

    protected function createTemporaryMirrorFile(): SplFileInfo
    {
        $splFileInfo = new SplFileInfo(Utils::createTemporaryFile(
            $this->isTemporaryMirrorFileCreatedInOriginalDirectory ? $this->file->getPath() : null,
            "{$this->getShortKebabName()}-",
            $this->file->getExtension()
        ));
        $splFileInfo->putContents($this->tokens->generateCode());

        return $splFileInfo;
    }
}
