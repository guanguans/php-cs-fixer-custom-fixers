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

use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\PintFixer;
use PhpCsFixer\Fixer\FixerInterface;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Reflection\ClassReflection;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @internal
 */
final class UpdateCodeSamplesRector extends UpdateCodeSamplesInFixerDefinitionRector
{
    public $key;

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
            || 'codeSamples' !== $scope->getFunctionName()
            || !($classReflection = $scope->getClassReflection()) instanceof ClassReflection
            || !$classReflection->is(FixerInterface::class)
            || !$classReflection->getNativeReflection()->isInstantiable()
            || (PintFixer::class === ($className = $classReflection->getName()) && \PHP_VERSION_ID < 80200)
        ) {
            return null;
        }

        $newItem = clone $node->items[0];

        // \RectorPrefix202512\dump_node($node);
        // \RectorPrefix202512\print_node($node);
        // dd();

        if (
            !($array = $newItem->value) instanceof Array_
            || (array_filter(
                $array->items,
                fn (ArrayItem $arrayItem): bool => $arrayItem->key instanceof String_ && 'code' === $this->key->value
            ))
            // || [] === ($args = $array->args)
            // || !($arg = $args[0]) instanceof Arg
            // || !($string = $arg->value) instanceof String_
        ) {
            return null;
        }

        // \RectorPrefix202512\dump_node($node);
        // \RectorPrefix202512\print_node($node);
        // dd();

        $newItem->value = clone $array;
        $newItem->value->items[0]->value->value = $fixedCode = $this->fixedCodeFor(new $className);

        // if (
        //     !isset($node->items[1])
        //     || !($array = $node->items[1]->value) instanceof New_
        //     || [] === ($args = $array->args)
        //     || !($arg = $args[0]) instanceof Arg
        //     || !($string = $arg->value) instanceof String_
        //     || $string->value !== $fixedCode
        // ) {
        //     $node->items[1] = $newItem;
        // }

        $node->items[1] = $newItem;

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
}
