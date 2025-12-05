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

namespace Guanguans\PhpCsFixerCustomFixers\Support;

use Composer\Autoload\ClassLoader;
use Illuminate\Support\Collection;

if (!\function_exists('Guanguans\PhpCsFixerCustomFixers\Support\classes')) {
    /**
     * @see https://github.com/illuminate/collections
     * @see https://github.com/alekitto/class-finder
     * @see https://github.com/ergebnis/classy
     * @see https://gitlab.com/hpierce1102/ClassFinder
     * @see https://packagist.org/packages/haydenpierce/class-finder
     * @see \get_declared_classes()
     * @see \get_declared_interfaces()
     * @see \get_declared_traits()
     * @see \DG\BypassFinals::enable()
     * @see \Composer\Util\ErrorHandler
     * @see \Monolog\ErrorHandler
     * @see \PhpCsFixer\ExecutorWithoutErrorHandler
     * @see \Phrity\Util\ErrorHandler
     *
     * @noinspection RedundantDocCommentTagInspection
     * @noinspection PhpUndefinedNamespaceInspection
     *
     * @internal
     *
     * @param null|(callable(class-string, string): bool) $filter
     *
     * @return \Illuminate\Support\Collection<class-string, \ReflectionClass>
     */
    function classes(?callable $filter = null): Collection
    {
        static $classes;

        $classes ??= collect(spl_autoload_functions())->flatMap(
            static fn ($loader): array => \is_array($loader) && $loader[0] instanceof ClassLoader
                ? $loader[0]->getClassMap()
                : []
        );

        return $classes
            ->when(
                \is_callable($filter),
                static fn (Collection $classes): Collection => $classes->filter(
                    static fn (string $file, string $class) => $filter($class, $file)
                )
            )
            ->mapWithKeys(static function (string $file, string $class): array {
                try {
                    return [$class => new \ReflectionClass($class)];
                } catch (\Throwable $throwable) {
                    return [$class => $throwable];
                }
            });
    }
}
