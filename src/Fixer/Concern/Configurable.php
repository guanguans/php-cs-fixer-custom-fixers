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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\Concern;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use PhpCsFixer\Fixer\ConfigurableFixerTrait;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;

trait Configurable
{
    use ConfigurableFixerTrait;

    /**
     * @throws \ReflectionException
     */
    final protected function createConfigurationDefinition(): FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver(
            collect($this->allPrivateReflectionMethods())
                ->filter(
                    static fn (\ReflectionMethod $method): bool => !$method->isAbstract()
                        && !$method->isStatic()
                        && $method->isUserDefined()
                        && Str::of($method->getName())->is([
                            // 'fixerOption',
                            'fixerOptionOf*',
                            'fixerOptions',
                            // 'fixerOptionsOf*',
                        ])
                )
                ->flatMap(function (\ReflectionMethod $method): array {
                    if (\PHP_VERSION_ID < 80100) {
                        $method->setAccessible(true);
                    }

                    return Arr::wrap($method->invoke($this));
                })
                // ->dd()
                ->all()
        );
    }

    /**
     * @throws \ReflectionException
     *
     * @return list<\ReflectionMethod>
     */
    private function allPrivateReflectionMethods(): array
    {
        $reflectionObject = new \ReflectionObject($this);
        $methods = [];

        do {
            $methods[] = $reflectionObject->getMethods(\ReflectionMethod::IS_PRIVATE);
        } while (\PHP_VERSION_ID >= 80000 && $reflectionObject = $reflectionObject->getParentClass());

        return array_merge(...$methods);
    }
}
