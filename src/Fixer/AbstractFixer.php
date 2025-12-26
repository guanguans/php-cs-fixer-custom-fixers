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

use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\ConcreteName;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\Testable;
use Guanguans\PhpCsFixerCustomFixers\Support\Traits\MakeStaticable;

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
    use Testable;

    /** @see \PhpCsFixer\WhitespacesFixerConfig::__construct() */
    protected const ALLOWED_VALUES_OF_INDENT = ['  ', '    ', "\t"];
    protected const ALLOWED_VALUES_OF_LINE_ENDING = ["\n", "\r\n"];
}
