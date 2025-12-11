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

namespace Guanguans\PhpCsFixerCustomFixers;

use Guanguans\PhpCsFixerCustomFixers\Support\Traits\MakeStaticable;
use PhpCsFixer\Finder;
use PhpCsFixer\Fixer\FixerInterface;

/**
 * @implements \IteratorAggregate<FixerInterface>
 *
 * @see \PhpCsFixerCustomFixers\Fixers
 * @see \ErickSkrauch\PhpCsFixer\Fixers
 */
final class Fixers implements \IteratorAggregate
{
    use MakeStaticable;

    /**
     * @throws \ReflectionException
     *
     * @return \Generator<FixerInterface>
     */
    public function getIterator(): \Traversable
    {
        foreach ((Finder::create())->in(__DIR__.'/Fixer')->name('*Fixer.php') as $file) {
            // -4 is set to cut ".php" extension
            $class = __NAMESPACE__.str_replace('/', '\\', mb_substr($file->getPathname(), mb_strlen(__DIR__), -4));

            if (
                !is_subclass_of($class, FixerInterface::class)
                || !($reflectionClass = new \ReflectionClass($class))->isInstantiable()
                || (
                    $reflectionClass->getConstructor() instanceof \ReflectionMethod
                    && $reflectionClass->getConstructor()->getNumberOfRequiredParameters() > 0
                )
            ) {
                continue;
            }

            yield new $class;
        }
    }

    /**
     * @return list<string>
     */
    public function extensionPatterns(): array
    {
        return array_unique($this->aggregate(__FUNCTION__));
    }

    /**
     * @return list<string>
     */
    public function extensions(): array
    {
        return array_unique($this->aggregate(__FUNCTION__));
    }

    /**
     * @return list<mixed>
     */
    public function aggregate(string $method): array
    {
        return array_merge(...array_map(
            static fn (FixerInterface $fixer): array => method_exists($fixer, $method) ? $fixer->{$method}() : [],
            iterator_to_array($this),
        ));
    }
}
