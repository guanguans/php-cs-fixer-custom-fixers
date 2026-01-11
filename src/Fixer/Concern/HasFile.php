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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\Concern;

use Guanguans\PhpCsFixerCustomFixers\Support\Utils;

trait HasFile
{
    protected \SplFileInfo $file;
    protected string $filePath;

    public function getFile(): \SplFileInfo
    {
        return $this->file;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    protected function setFile(\SplFileInfo $file): void
    {
        $this->file = $file;
    }

    protected function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
    }

    protected function finalFilePath(): string
    {
        $filePath = (string) $this->file;

        if (Utils::isDryRun()) {
            $filePath = $this->createTemporaryFile();
            file_put_contents($filePath, $this->tokens->generateCode());
        }

        // file_put_contents(getcwd().'/tests.log', implode(\PHP_EOL, [
        //     $filePath,
        //     json_encode(Utils::isDryRun()),
        //     json_encode($_SERVER['argv'], \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE),
        // ]));

        return $filePath;
    }

    protected function createTemporaryFile(
        ?string $directory = null,
        ?string $prefix = null,
        ?string $extension = null,
        bool $deferDelete = true
    ): string {
        return Utils::createTemporaryFile(
            $directory,
            $prefix ?? "{$this->getShortKebabName()}-",
            $extension ?? $this->file->getExtension(),
            $deferDelete,
        );
    }
}
