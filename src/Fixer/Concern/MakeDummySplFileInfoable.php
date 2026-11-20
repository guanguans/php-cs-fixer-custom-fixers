<?php

/** @noinspection PhpInternalEntityUsedInspection */
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

trait MakeDummySplFileInfoable
{
    /**
     * @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ZhlintFixer
     * @see \Guanguans\PhpCsFixerCustomFixers\Support\Utils::createDummyFixerTemporaryFileInfo()
     *
     * @internal
     */
    public function makeDummySplFileInfo(): \SplFileInfo
    {
        return new SplFileInfo(Utils::fixturesPath("dummy-{$this->getShortKebabName()}.{$this->randomExtension()}"));
    }
}
