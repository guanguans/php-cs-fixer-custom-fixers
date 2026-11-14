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
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;
use PhpCsFixer\StdinFileInfo;

trait Testable
{
    /**
     * @see https://github.com/bovigo/vfsStream
     * @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concern\HasTemporaryMirrorFile::createTemporaryMirrorFile()
     * @see \PhpCsFixer\StdinFileInfo
     * @see \PhpCsFixer\Tests\Test\AbstractFixerTestCase::testFixerDefinitions()
     */
    public function makeDummySplFileInfo(?string $extension = null): \SplFileInfo
    {
        return $this instanceof AbstractInlineHtmlFixer
            ? new \SplFileInfo(Utils::createTemporaryFile(
                getcwd().'/tests/Fixtures/',
                "{$this->getShortKebabName()}-",
                $extension ?? $this->randomExtension()
            ))
            : new StdinFileInfo;
    }
}
