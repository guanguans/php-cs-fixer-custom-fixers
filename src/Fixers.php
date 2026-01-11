<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 guanguans<ityaozm@gmail.com>
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
 * @api
 *
 * @see \PhpCsFixerCustomFixers\Fixers
 * @see \ErickSkrauch\PhpCsFixer\Fixers
 *
 * @implements \IteratorAggregate<\Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer>
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
        foreach ((Finder::create())->in(__DIR__.'/Fixer')->name('*Fixer.php')->sortByName() as $file) {
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
    public function dependencyCommands(): array
    {
        return collect($this->aggregate(Pluralizer::singular(__FUNCTION__)))
            ->sort(static fn (string $a, string $b): int => strcasecmp($a, $b))
            ->all();
    }

    /**
     * @return list<string>
     */
    public function getDependencyNames(): array
    {
        return collect($this->aggregate(Pluralizer::singular(__FUNCTION__)))
            ->sort(static fn (string $a, string $b): int => strcasecmp($a, $b))
            ->all();
    }

    /**
     * @return list<string>
     */
    public function extensionPatterns(): array
    {
        return collect(array_merge(...$this->aggregate(__FUNCTION__)))
            ->unique()
            ->sort(static fn (string $a, string $b): int => strcasecmp($a, $b))
            ->all();
    }

    /**
     * @return list<string>
     */
    public function extensions(): array
    {
        return collect(array_merge(...$this->aggregate(__FUNCTION__)))
            ->unique()
            ->sort(static fn (string $a, string $b): int => strcasecmp($a, $b))
            ->all();
    }

    /**
     * @param mixed ...$arguments
     *
     * @return list<mixed>
     */
    private function aggregate(string $method, ...$arguments): array
    {
        return collect($this)->reduce(
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
