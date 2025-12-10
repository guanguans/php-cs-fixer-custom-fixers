<?php

/** @noinspection EfferentObjectCouplingInspection */

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
class UpdateCodeSamplesInFixerDefinitionRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getNodeTypes(): array
    {
        return [
            Array_::class,
        ];
    }

    /**
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     *
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
            || (PintFixer::class === ($className = $classReflection->getName()) && \PHP_VERSION_ID < 80200)
        ) {
            return null;
        }

        $arrayItem = clone $node->items[0];

        if (
            !($exprOfnew = $arrayItem->value) instanceof New_
            || [] === ($args = $exprOfnew->args)
            || !($arg = $args[0]) instanceof Arg
            || !($scalarOfString = $arg->value) instanceof String_
        ) {
            return null;
        }

        $arrayItem->value = clone $exprOfnew;
        $arrayItem->value->args[0] = clone $arg;
        $arrayItem->value->args[0]->value = clone $scalarOfString;
        $arrayItem->value->args[0]->value->value = $fixedCode = $this->fixedCodeFor(new $className);

        if (
            !isset($node->items[1])
            || !($exprOfnew = $node->items[1]->value) instanceof New_
            || [] === ($args = $exprOfnew->args)
            || !($arg = $args[0]) instanceof Arg
            || !($scalarOfString = $arg->value) instanceof String_
            || $scalarOfString->value !== $fixedCode
        ) {
            $node->items[1] = $arrayItem;
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
                                    $summary = \sprintf('Format `%s` files.', $this->firstExtension()),
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
                                    $summary = \sprintf('Format `%s` files.', $this->firstExtension()),
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

    protected function fixedCodeFor(AbstractFixer $fixer): string
    {
        if ($fixer instanceof WhitespacesAwareFixerInterface) {
            $fixer->setWhitespacesConfig(new WhitespacesFixerConfig);
        }

        $codeSample = $fixer->getDefinition()->getCodeSamples()[0];
        \assert($codeSample instanceof CodeSampleInterface);

        if ($fixer instanceof ConfigurableFixerInterface) {
            $fixer->configure($codeSample->getConfiguration() ?? []);
        }

        $tokens = Tokens::fromCode($codeSample->getCode());

        $this->wrappedInDryRun(static fn () => $fixer->fix(
            new \SplFileInfo(\sprintf(
                '%s%sfile.%s',
                getcwd(),
                \DIRECTORY_SEPARATOR,
                method_exists($fixer, 'randomExtension') ? $fixer->randomExtension() : 'php'
            )),
            $tokens
        ));

        return $tokens->generateCode();
    }

    /**
     * @param \Closure(): void $callback
     */
    private function wrappedInDryRun(\Closure $callback): void
    {
        if ($doesntExistDryRun = !\in_array($dryRun = '--dry-run', $_SERVER['argv'], true)) {
            $_SERVER['argv'][] = $dryRun;
        }

        $callback();

        $doesntExistDryRun and $_SERVER['argv'] = array_filter(
            $_SERVER['argv'],
            static fn ($value): bool => $dryRun !== $value,
        );
    }
}
