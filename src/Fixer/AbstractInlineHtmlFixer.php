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

namespace Guanguans\PhpCsFixerCustomFixers\Fixer;

use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\AllowRisky;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\CandidateOfInlineHtml;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\ConfigurableOfExtensions;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\DefinitionOfExtensions;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\HighestPriority;
use Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\SupportsOfExtensions;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;

abstract class AbstractInlineHtmlFixer extends AbstractConfigurableFixer /* implements WhitespacesAwareFixerInterface */
{
    use AllowRisky;
    use CandidateOfInlineHtml;
    use ConfigurableOfExtensions;
    use DefinitionOfExtensions;
    use HighestPriority;
    use SupportsOfExtensions;

    /** @see \Guanguans\PhpCsFixerCustomFixers\Fixer\Concern\ConfigurableOfExtensions */
    public const EXTENSIONS = 'extensions';
}
