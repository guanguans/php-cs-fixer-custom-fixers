<?php

/** @noinspection AnonymousFunctionStaticInspection */
/** @noinspection NullPointerExceptionInspection */
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
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;
use PhpCsFixer\Tokenizer\Tokens;

it('can diagnosis `isCandidate` and `supports`', function (): void {
    expect($fixer = new AggregateCommandLineToolFixer)
        ->configure([
            AggregateCommandLineToolFixer::CONFIGURATIONS => [
                [
                    /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DockerfmtFixer */
                    AbstractCommandLineToolFixer::COMMAND => ['dockerfmt'],
                    AbstractCommandLineToolFixer::OPTIONS => ['--newline' => true, '--space-redirects' => true, '--write' => true],
                    AbstractInlineHtmlFixer::EXTENSIONS => ['Dockerfile'],
                ],
                [
                    /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\DotenvLinterFixer */
                    AbstractCommandLineToolFixer::COMMAND => ['dotenv-linter', 'fix'],
                    // AbstractCommandLineToolFixer::OPTIONS => [],
                    AbstractInlineHtmlFixer::EXTENSIONS => ['env', 'env.example'],
                ],
                [
                    /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\ShfmtFixer */
                    AbstractCommandLineToolFixer::COMMAND => ['shfmt'],
                    AbstractCommandLineToolFixer::OPTIONS => ['--write' => true],
                    AbstractInlineHtmlFixer::EXTENSIONS => ['bats', 'sh'],
                    AggregateCommandLineToolFixer::IS_POST_TEMPORARY_MIRROR_FILE_PATH_COMMAND => true,
                ],
            ],
        ])->toBeNull()
        ->isCandidate(Tokens::fromCode('<?php'))->toBeFalse()
        ->supports(Utils::createDummyFixerTemporaryFileInfo($fixer, null, null, 'php'))->toBeFalse();
})->group(__DIR__, __FILE__);
