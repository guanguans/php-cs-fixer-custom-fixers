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

use Guanguans\PhpCsFixerCustomFixers\Support\SplFileInfo;
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;

trait HasTemporaryFile
{
    protected SplFileInfo $temporaryFile;

    public function getTemporaryFile(): SplFileInfo
    {
        return $this->temporaryFile ??= (function (): SplFileInfo {
            $splFileInfo = new SplFileInfo($this->createTemporaryFile());
            $splFileInfo->putContents($this->tokens->generateCode());

            return $splFileInfo;
        })();
    }

    protected function setTemporaryFile(SplFileInfo $temporaryFile): void
    {
        $this->temporaryFile = $temporaryFile;
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
            $extension ?? $this->temporaryFile->getExtension(),
            $deferDelete,
        );
    }
}
