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

namespace Guanguans\PhpCsFixerCustomFixers\Support\Rectors;

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer;
use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\PintFixer;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerDefinition\CodeSampleInterface;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\WhitespacesFixerConfig;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Reflection\ClassReflection;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @internal
 */
final class UpdateFixedCodeSampleRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getNodeTypes(): array
    {
        return [
            Array_::class,
        ];
    }

    /**
     * @param \PhpParser\Node\Expr\Array_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $scope = $node->getAttribute('scope');
        \assert($scope instanceof MutatingScope);

        if (
            [] === $node->items
            || 'getDefinition' !== $scope->getFunctionName()
            || !($classReflection = $scope->getClassReflection()) instanceof ClassReflection
            || !$classReflection->is(FixerInterface::class)
            || !$classReflection->getNativeReflection()->isInstantiable()
        ) {
            return null;
        }

        $newItem = clone $node->items[0];

        if (
            !($new = $newItem->value) instanceof New_
            || [] === ($args = $new->args)
            || !($arg = $args[0]) instanceof Arg
            || !($string = $arg->value) instanceof String_
        ) {
            return null;
        }

        $className = $classReflection->getName();

        if (PintFixer::class === $className && \PHP_VERSION_ID < 80200) {
            return null;
        }

        $newItem->value = clone $new;
        $newItem->value->args[0] = clone $arg;
        $newItem->value->args[0]->value = clone $string;
        $newItem->value->args[0]->value->value = $fixedCode = $this->fixedCodeFor(new $className);

        if (
            !isset($node->items[1])
            || !($new = $node->items[1]->value) instanceof New_
            || [] === ($args = $new->args)
            || !($arg = $args[0]) instanceof Arg
            || !($string = $arg->value) instanceof String_
            || $string->value !== $fixedCode
        ) {
            $node->items[1] = $newItem;
        }

        return $node;
    }

    /**
     * @throws \Symplify\RuleDocGenerator\Exception\PoorDocumentationException
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Update fixed code sample rector',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
                        final class JsonFixer extends AbstractInlineHtmlFixer
                        {
                            public function getDefinition(): FixerDefinitionInterface
                            {
                                return new FixerDefinition(
                                    $summary = \sprintf('Format `%s` files.', $this->defaultExtensions()[0]),
                                    [
                                        new CodeSample(
                                            <<<'JSON'
                                                {
                                                "foo": "bar",
                                                    "baz": {
                                                "qux": "quux"
                                                    }
                                                }
                                                JSON
                                        )
                                    ],
                                    $summary,
                                    'Affected by JSON encoding/decoding functions.'
                                );
                            }
                        }
                        CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
                        final class JsonFixer extends AbstractInlineHtmlFixer
                        {
                            public function getDefinition(): FixerDefinitionInterface
                            {
                                return new FixerDefinition(
                                    $summary = \sprintf('Format `%s` files.', $this->defaultExtensions()[0]),
                                    [
                                        new CodeSample(
                                            <<<'JSON'
                                                {
                                                "foo": "bar",
                                                    "baz": {
                                                "qux": "quux"
                                                    }
                                                }
                                                JSON
                                        ), new CodeSample(
                                            <<<'JSON'
                                            {
                                                "foo": "bar",
                                                "baz": {
                                                    "qux": "quux"
                                                }
                                            }
                                            JSON
                                        ),
                                    ],
                                    $summary,
                                    'Affected by JSON encoding/decoding functions.'
                                );
                            }
                        }
                        CODE_SAMPLE,
                ),
            ],
        );
    }

    private function fixedCodeFor(AbstractFixer $fixer): string
    {
        if ($fixer instanceof WhitespacesAwareFixerInterface) {
            $fixer->setWhitespacesConfig(new WhitespacesFixerConfig);
        }

        $codeSample = $fixer->getDefinition()->getCodeSamples()[0];
        \assert($codeSample instanceof CodeSampleInterface);

        $originalCode = $codeSample->getCode();

        if ($fixer instanceof ConfigurableFixerInterface) {
            $fixer->configure($codeSample->getConfiguration() ?? []);
        }

        $tokens = Tokens::fromCode($originalCode);
        $this->wrapInDryRunning(fn () => $fixer->fix($this->createSplFileInfoDouble($fixer), $tokens));

        return $tokens->generateCode();
    }

    /**
     * @param \Closure(): mixed $callback
     *
     * @return mixed
     */
    private function wrapInDryRunning(\Closure $callback)
    {
        $dryRun = '--dry-run';

        if (!\in_array($dryRun, $_SERVER['argv'], true)) {
            $_SERVER['argv'][] = '--dry-run';
        }

        $result = $callback();

        $_SERVER['argv'] = array_filter(
            $_SERVER['argv'],
            static fn ($value): bool => '--dry-run' !== $value,
        );

        return $result;
    }

    private function createSplFileInfoDouble(AbstractFixer $fixer): \SplFileInfo
    {
        $ext = method_exists($fixer, 'firstDefaultExtension') ? $fixer->firstDefaultExtension() : 'php';

        return new class(getcwd().\DIRECTORY_SEPARATOR.'file.'.$ext) extends \SplFileInfo {};
    }
}
