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
            collect((new \ReflectionObject($this))->getMethods(\ReflectionMethod::IS_PRIVATE))
                // ->dd()
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
                    $method->setAccessible(true);

                    return Arr::wrap($method->invoke($this));
                })
                // ->dd()
                ->all()
        );
    }
}
