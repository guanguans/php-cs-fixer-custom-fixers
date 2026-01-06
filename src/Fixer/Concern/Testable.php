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

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer;
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;
use PhpCsFixer\StdinFileInfo;

trait Testable
{
    /**
     * @see https://github.com/bovigo/vfsStream
     * @see \PhpCsFixer\StdinFileInfo
     * @see \PhpCsFixer\Tests\Test\AbstractFixerTestCase::testFixerDefinitions()
     */
    public function makeDummySplFileInfo(?string $extension = null): \SplFileInfo
    {
        if ($this instanceof AbstractCommandLineToolFixer && !Utils::isDryRun()) {
            Utils::dummyDryRun();
        }

        return $this instanceof AbstractInlineHtmlFixer
            ? new \SplFileInfo(\sprintf('%sfile.%s', getcwd().\DIRECTORY_SEPARATOR, $extension ?? $this->randomExtension()))
            : new StdinFileInfo;
    }
}
