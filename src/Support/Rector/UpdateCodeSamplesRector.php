<?php

/** @noinspection EfferentObjectCouplingInspection */

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

namespace Guanguans\PhpCsFixerCustomFixers\Support\Rector;

use Guanguans\PhpCsFixerCustomFixers\Fixer\AbstractFixer;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerDefinition\CodeSampleInterface;
use PhpCsFixer\FixerDefinition\VersionSpecificCodeSampleInterface;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\WhitespacesFixerConfig;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Reflection\ClassReflection;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @internal
 */
final class UpdateCodeSamplesRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getNodeTypes(): array
    {
        return [
            Array_::class,
        ];
    }

    /**
     * @param \PhpParser\Node\Expr\Array_ $node
     *
     * @throws \Rector\Exception\ShouldNotHappenException
     * @throws \ReflectionException
     *
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     * @noinspection NotOptimalIfConditionsInspection
     */
    public function refactor(Node $node): ?Node
    {
        // $scope = $node->getAttribute(AttributeKey::SCOPE);
        $scope = ScopeFetcher::fetch($node);
        // \assert($scope instanceof MutatingScope);

        if (
            [] === $node->items
            || !\in_array($scope->getFunctionName(), ['getDefinition', 'codeSamples'], true)
            || !($classReflection = $scope->getClassReflection()) instanceof ClassReflection
            || !$classReflection->is(AbstractFixer::class)
            || !$classReflection->getNativeReflection()->isInstantiable()
            || (
                $classReflection->getNativeReflection()->getConstructor() instanceof \ReflectionMethod
                && $classReflection->getNativeReflection()->getConstructor()->getNumberOfRequiredParameters() > 0
            )
        ) {
            return null;
        }

        $fixer = $classReflection->getNativeReflection()->newInstance();
        \assert($fixer instanceof AbstractFixer);
        $codeSample = $fixer->getDefinition()->getCodeSamples()[0];

        if ($codeSample instanceof VersionSpecificCodeSampleInterface && !$codeSample->isSuitableFor(\PHP_VERSION_ID)) {
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
        $arrayItem->value->args[0]->value->value = $fixedCode = $this->fixedCodeFor($fixer);

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

        unset($node->items[1]);

        return $node;
    }

    /**
     * @throws \Symplify\RuleDocGenerator\Exception\PoorDocumentationException
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Update code samples rector',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
                        protected function codeSamples(): array
                        {
                            return [
                                new CodeSample(
                                    <<<'YAML_WRAP'
                                        on:
                                            issues:
                                                types: [ opened ]
                                        YAML_WRAP
                                )
                            ];
                        }
                        CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
                        protected function codeSamples(): array
                        {
                            return [
                                new CodeSample(
                                    <<<'YAML_WRAP'
                                        on:
                                            issues:
                                                types: [ opened ]
                                        YAML_WRAP
                                ), new CodeSample(
                                    <<<'YAML_WRAP'
                                        on:
                                          issues:
                                            types: [opened]

                                        YAML_WRAP
                                ),
                            ];
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

        if ($fixer instanceof ConfigurableFixerInterface) {
            $fixer->configure($codeSample->getConfiguration() ?? []);
        }

        $tokens = Tokens::fromCode($codeSample->getCode());
        $fixer->fix($fixer->makeDummySplFileInfo(), $tokens);

        return $tokens->generateCode();
    }
}
