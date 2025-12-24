<?php

/** @noinspection PhpInternalEntityUsedInspection */

declare(strict_types=1);

/**
 * Copyright (c) 2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

namespace Guanguans\PhpCsFixerCustomFixers\Fixer;

use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\ConcreteName;
use Guanguans\PhpCsFixerCustomFixers\Support\Traits\MakeStaticable;
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;
use PhpCsFixer\StdinFileInfo;

/**
 * @see \Guanguans\PhpCsFixerCustomFixers\Fixer
 * @see \Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer
 * @see \PhpCsFixer\AbstractFixer
 * @see \PhpCsFixer\Fixer
 * @see \PhpCsFixerCustomFixers\Fixer
 * @see \PhpCsFixerCustomFixers\Fixer\AbstractFixer
 * @see \Symplify\CodingStandard\Fixer\AbstractSymplifyFixer
 * @see \Symplify\CodingStandard\Fixer\Annotation
 *
 * @noinspection PhpUndefinedNamespaceInspection
 */
abstract class AbstractFixer extends \PhpCsFixer\AbstractFixer
{
    use ConcreteName;
    use MakeStaticable;

    /** @see \PhpCsFixer\WhitespacesFixerConfig::__construct() */
    protected const ALLOWED_VALUES_OF_INDENT = ['  ', '    ', "\t"];
    protected const ALLOWED_VALUES_OF_LINE_ENDING = ["\n", "\r\n"];

    /**
     * @see \PhpCsFixer\StdinFileInfo
     * @see \PhpCsFixer\Tests\Test\AbstractFixerTestCase::testFixerDefinitions()
     *
     * @noinspection PhpHierarchyChecksInspection
     */
    final public function makeDummySplFileInfo(): \SplFileInfo
    {
        if ($this instanceof AbstractCommandLineToolFixer && !Utils::isDryRun()) {
            /** @var array{argv: list<string>} $_SERVER */
            $_SERVER['argv'][] = '--dry-run';
        }

        return $this instanceof AbstractInlineHtmlFixer
            ? new \SplFileInfo(\sprintf('%sfile.%s', getcwd().\DIRECTORY_SEPARATOR, $this->randomExtension()))
            : new StdinFileInfo;
    }
}
