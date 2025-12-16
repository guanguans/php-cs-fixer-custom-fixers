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

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer;
use Guanguans\PhpCsFixerCustomFixers\Support\Traits\MakeStaticable;
use Illuminate\Support\Pluralizer;
use PhpCsFixer\Finder;

/**
 * @implements \IteratorAggregate<\Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer>
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
     * @return \Generator<\Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer>
     */
    public function getIterator(): \Traversable
    {
        foreach ((Finder::create())->in(__DIR__.'/Fixer')->name('*Fixer.php') as $file) {
            // -4 is set to cut ".php" extension
            $class = __NAMESPACE__.str_replace('/', '\\', mb_substr($file->getPathname(), mb_strlen(__DIR__), -4));

            if (
                !is_subclass_of($class, AbstractFixer::class)
                || !($reflectionClass = new \ReflectionClass($class))->isInstantiable()
                || (
                    $reflectionClass->getConstructor() instanceof \ReflectionMethod
                    && $reflectionClass->getConstructor()->getNumberOfRequiredParameters() > 0
                )
            ) {
                continue;
            }

            // yield new $class;
            yield $reflectionClass->newInstance();
        }
    }

    /**
     * @return list<string>
     */
    public function getAliasNames(): array
    {
        return $this->aggregate(Pluralizer::singular(__FUNCTION__));
    }

    /**
     * @return list<string>
     */
    public function extensionPatterns(): array
    {
        return array_unique(array_merge(...$this->aggregate(__FUNCTION__)));
    }

    /**
     * @return list<string>
     */
    public function extensions(): array
    {
        return array_unique(array_merge(...$this->aggregate(__FUNCTION__)));
    }

    /**
     * @param mixed ...$arguments
     *
     * @return list<mixed>
     */
    public function aggregate(string $method, ...$arguments): array
    {
        return array_reduce(
            iterator_to_array($this),
            static function (array $carry, AbstractFixer $fixer) use ($method, $arguments): array {
                if (method_exists($fixer, $method)) {
                    $carry[] = $fixer->{$method}(...$arguments);
                }

                return $carry;
            },
            [],
        );
    }
}
