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

namespace Guanguans\PhpCsFixerCustomFixers\Support;

use Composer\Autoload\ClassLoader;
use Illuminate\Support\Collection;
use Symfony\Component\Process\PhpExecutableFinder;

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
     * @see \Composer\Util\ErrorHandler
     * @see \Composer\Util\Silencer::call()
     * @see \DG\BypassFinals::enable()
     * @see \Illuminate\Foundation\Bootstrap\HandleExceptions::bootstrap()
     * @see \Monolog\ErrorHandler
     * @see \PhpCsFixer\ExecutorWithoutErrorHandler
     * @see \Phrity\Util\ErrorHandler
     *
     * @template TObject of object
     *
     * @internal
     *
     * @param null|(callable(class-string<TObject>, string): bool) $filter
     *
     * @throws \ErrorException
     *
     * @return \Illuminate\Support\Collection<class-string<TObject>, \ReflectionClass<TObject>|\Throwable>
     *
     * @noinspection PhpUndefinedNamespaceInspection
     */
    function classes(?callable $filter = null): Collection
    {
        $filter ??= static fn (string $class, string $file): bool => true;

        /** @var null|\Illuminate\Support\Collection<string, class-string> $classes */
        static $classes;
        $classes ??= collect(spl_autoload_functions())->flatMap(
            static fn (callable $loader): array => \is_array($loader) && $loader[0] instanceof ClassLoader
                ? $loader[0]->getClassMap()
                : []
        );

        /** @var null|array{class: class-string<TObject>, line: int} $context */
        static $context = null;
        static $registered = false;

        if (!$registered) {
            register_shutdown_function(
                static function (string $func) use (&$context): void {
                    // @codeCoverageIgnoreStart
                    if (
                        null === $context
                        || null === ($error = error_get_last())
                        || !\in_array($error['type'], [\E_COMPILE_ERROR, \E_CORE_ERROR, \E_ERROR, \E_PARSE], true)
                    ) {
                        return;
                    }

                    // trigger_error('Error message...', \E_USER_ERROR);
                    throw new \ErrorException(
                        "Fatal Error detected during reflection of class [{$context['class']}]".\PHP_EOL.
                        "You may need to filter out the class using the callback parameter of the function [$func()]",
                        0,
                        $error['type'],
                        __FILE__,
                        $context['line'],
                        new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line'])
                    );
                    // @codeCoverageIgnoreEnd
                },
                __FUNCTION__
            );

            $registered = true;
        }

        return $classes
            ->filter(static fn (string $file, string $class): bool => $filter($class, $file))
            ->mapWithKeys(static function (string $file, string $class) use (&$context): array {
                try {
                    $context = ['class' => $class, 'line' => __LINE__ + 2];

                    return [$class => new \ReflectionClass($class)];
                } catch (\Throwable $throwable) {
                    return [$class => $throwable];
                } finally {
                    $context = null;
                }
            });
    }
}

if (!\function_exists('Guanguans\PhpCsFixerCustomFixers\Support\php_binary')) {
    function php_binary(): string
    {
        return (new PhpExecutableFinder)->find(false) ?: 'php';
    }
}
