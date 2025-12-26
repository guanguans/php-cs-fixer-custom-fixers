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

namespace Guanguans\PhpCsFixerCustomFixers\FixerDefinition;

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\CodeSampleInterface;
use PhpCsFixer\FixerDefinition\FileSpecificCodeSampleInterface;

/**
 * @readonly
 *
 * @internal
 */
final class FileSpecificCodeSample implements FileSpecificCodeSampleInterface
{
    private CodeSampleInterface $codeSample;
    private AbstractFixer $fixer;

    /**
     * @param null|array<string, mixed> $configuration
     */
    public function __construct(
        string $code,
        AbstractFixer $fixer,
        ?array $configuration = null
    ) {
        $this->codeSample = new CodeSample($code, $configuration);
        $this->fixer = $fixer;
    }

    public function getCode(): string
    {
        return $this->codeSample->getCode();
    }

    public function getConfiguration(): ?array
    {
        return $this->codeSample->getConfiguration();
    }

    public function getSplFileInfo(): \SplFileInfo
    {
        return $this->fixer->makeDummySplFileInfo();
    }
}
