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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\Concern;

use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;

/**
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer
 * @mixin \Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\Configurable
 *
 * @property array{
 *     single_blank_line_at_eof: list<string>,
 * } $configuration
 */
trait ConfigurableOfSingleBlankLineAtEof
{
    // /** @var string */
    // public const SINGLE_BLANK_LINE_AT_EOF = 'single_blank_line_at_eof';

    /**
     * @see \PhpCsFixer\Fixer\Whitespace\SingleBlankLineAtEofFixer
     *
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function fixerOptionOfSingleBlankLineAtEof(): FixerOptionInterface
    {
        return (new FixerOptionBuilder(self::SINGLE_BLANK_LINE_AT_EOF, 'The line ending to use at the end of the file.'))
            ->setAllowedTypes(['string', 'null'])
            ->setAllowedValues(array_merge(self::ALLOWED_VALUES_OF_LINE_ENDING, [null]))
            ->setDefault($this instanceof AbstractCommandLineToolFixer ? null : "\n")
            ->getOption();
    }
}
