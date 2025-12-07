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

use Guanguans\PhpCsFixerCustomFixers\Fixer\Concerns\AlwaysCandidate;
use Illuminate\Support\Str;

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
    use AlwaysCandidate;

    public static function name(): string
    {
        return (new static)->getName();
    }

    public function getName(): string
    {
        return "Guanguans/{$this->getShortName()}";
    }

    public function getShortHeadlineName(): string
    {
        return (string) Str::of($this->getShortName())->headline();
    }

    public function getShortName(): string
    {
        return parent::getName();
    }
}
