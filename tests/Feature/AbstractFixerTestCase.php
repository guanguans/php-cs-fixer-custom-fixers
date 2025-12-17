<?php

/** @noinspection MissingParentCallInspection */
/** @noinspection PhpInternalEntityUsedInspection */
/** @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

/**
 * Copyright (c) 2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

namespace Guanguans\PhpCsFixerCustomFixersTests\Feature;

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixers;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class AbstractFixerTestCase extends \PhpCsFixer\Tests\Test\AbstractFixerTestCase
{
    protected function createFixer(): AbstractFixer
    {
        static $fixers;

        $name = (string) Str::of((new \ReflectionClass(static::class))->getShortName())->whenEndsWith(
            $suffix = 'Test',
            static fn (Stringable $file): Stringable => $file->replaceLast($suffix, '')
        );

        return ($fixers ??= collect(Fixers::make()))->firstOrFail(
            fn (AbstractFixer $fixer): bool => $fixer->getShortClassName() === $name
        );
    }
}
