<?php

/** @noinspection PhpDocFinalChecksInspection */
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

namespace Guanguans\PhpCsFixerCustomFixers\Exception;

use Guanguans\PhpCsFixerCustomFixers\Contract\ThrowableContract;
use PhpCsFixer\ConfigurationException\InvalidFixerConfigurationException as PhpCsFixerInvalidFixerConfigurationException;

/**
 * @see \PhpCsFixer\Fixer\ConfigurableFixerTrait
 */
final class InvalidFixerConfigurationException extends PhpCsFixerInvalidFixerConfigurationException implements ThrowableContract {}
