<?php

/** @noinspection AnonymousFunctionStaticInspection */
/** @noinspection NullPointerExceptionInspection */
/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpVoidFunctionResultUsedInspection */
/** @noinspection SqlResolve */
/** @noinspection StaticClosureCanBeUsedInspection */
declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractInlineHtmlFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AbstractCommandLineToolFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\AggregateCommandLineToolFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ShfmtFixer;
use PhpCsFixer\Tokenizer\Tokens;

it('can diagnosis `isCandidate` and `supports`', function (): void {
    expect($fixer = new AggregateCommandLineToolFixer)
        ->configure([
            AggregateCommandLineToolFixer::CONFIGURATIONS => [
                [
                    AbstractCommandLineToolFixer::COMMAND => (fn (): array => $this->defaultCommand())->call(ShfmtFixer::make()),
                    AbstractCommandLineToolFixer::OPTIONS => (fn (): array => $this->requiredOptions())->call(ShfmtFixer::make()),
                    AbstractInlineHtmlFixer::EXTENSIONS => (fn (): array => $this->defaultExtensions())->call(ShfmtFixer::make()),
                    AggregateCommandLineToolFixer::IS_POST_TEMPORARY_MIRROR_FILE_PATH_COMMAND => true,
                ],
            ],
        ])->toBeNull()
        ->isCandidate(Tokens::fromCode('<?php'))->toBeFalse()
        ->supports($fixer->makeDummySplFileInfo())->toBeFalse();
})->group(__DIR__, __FILE__);
