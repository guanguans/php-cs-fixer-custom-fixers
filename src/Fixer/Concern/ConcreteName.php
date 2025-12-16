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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\Concern;

use Guanguans\PhpCsFixerCustomFixers\Support\Traits\MakeStaticable;
use Illuminate\Support\Str;

/**
 * @phpstan-require-extends \PhpCsFixer\AbstractFixer
 */
trait ConcreteName
{
    use MakeStaticable;

    /**
     * @param mixed ...$parameters
     */
    public static function name(...$parameters): string
    {
        return self::make(...$parameters)->getName();
    }

    public function getName(): string
    {
        return "Guanguans/{$this->getShortName()}";
    }

    public function getAliasName(): string
    {
        return $this->getShortKebabName();
    }

    /**
     * @see https://github.com/jawira/case-converter
     */
    public function getShortKebabName(): string
    {
        return (string) Str::of($this->getShortName())->kebab()->replace('_', '-');
    }

    public function getShortName(): string
    {
        return parent::getName();
    }
}
