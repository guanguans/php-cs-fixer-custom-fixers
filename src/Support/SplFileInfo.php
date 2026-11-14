<?php

/** @noinspection ContractViolationInspection */
/** @noinspection PhpInternalEntityUsedInspection */
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

use Guanguans\PhpCsFixerCustomFixers\Exception\RuntimeException;
use PhpCsFixer\ExecutorWithoutErrorHandler;
use PhpCsFixer\ExecutorWithoutErrorHandlerException;

final class SplFileInfo extends \SplFileInfo
{
    /**
     * @see \Fidry\CpuCoreCounter\FileReader\NativeFileReader::read()
     * @see \PhpCsFixer\FileReader::read()
     * @see \PHPStan\File\FileReader::read()
     *
     * @param null|resource $context
     */
    public function getContents(bool $useIncludePath = false, $context = null, int $offset = 0, ?int $length = null): string
    {
        return $this->call(
            /** @return false|string */
            fn () => null === $length
                ? file_get_contents($this->getPathname(), $useIncludePath, $context, $offset)
                : file_get_contents($this->getPathname(), $useIncludePath, $context, $offset, $length)
        );
    }

    /**
     * @param list<string>|resource|string $data
     * @param null|resource $context
     */
    public function putContents($data, int $flags = 0, $context = null): int
    {
        return $this->call(
            /** @return false|int */
            fn () => file_put_contents($this->getPathname(), $data, $flags, $context)
        );
    }

    /**
     * @see \Guanguans\RectorRules\Support\classes()
     * @see \Composer\Util\Silencer::call()
     * @see \PhpCsFixer\ExecutorWithoutErrorHandler
     * @see \Symfony\Component\Finder\SplFileInfo
     *
     * @template TResult
     *
     * @param callable(): TResult $callback
     *
     * @return mixed|TResult
     *
     * @noinspection PhpVariableNamingConventionInspection
     */
    private function call(callable $callback)
    {
        try {
            return ExecutorWithoutErrorHandler::execute($callback);
        } catch (ExecutorWithoutErrorHandlerException $executorWithoutErrorHandlerException) {
            throw new RuntimeException(
                $executorWithoutErrorHandlerException->getMessage(),
                $executorWithoutErrorHandlerException->getCode(),
                $executorWithoutErrorHandlerException
            );
        }
    }
}
