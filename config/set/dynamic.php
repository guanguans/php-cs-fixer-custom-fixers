<?php

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

use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet\RuleSet;
use Symplify\EasyCodingStandard\Config\ECSConfig;

/**
 * @see https://github.com/ecsphp/ecs/blob/13.2.19/src/Config/ECSConfig.php#L176-L191
 * @see https://github.com/ecsphp/ecs/pull/57
 * @see ECSConfig::dynamicSets()
 */
return static function (ECSConfig $ecsConfig): void {
    $ruleSet = new RuleSet(array_fill_keys(
        [
            '@auto',
            '@auto:risky',
            // '@autoPHPMigration',
            // '@autoPHPMigration:risky',
            // '@autoPHPUnitMigration:risky',
            // '@DoctrineAnnotation',
            // '@PHP7x4Migration',
            // '@PHP7x4Migration:risky',
            // '@PHP8x0Migration',
            // '@PHP8x0Migration:risky',
            // '@PHP8x1Migration',
            // '@PHP8x1Migration:risky',
            // '@PHP8x2Migration',
            // '@PHP8x2Migration:risky',
            // '@PHP8x3Migration',
            // '@PHP8x3Migration:risky',
            // '@PHP8x4Migration',
            // '@PHP8x4Migration:risky',
            // '@PHP8x5Migration',
            // '@PHP8x5Migration:risky',
            '@PhpCsFixer',
            '@PhpCsFixer:risky',
            // '@PHPUnit8x4Migration:risky',
            // '@PHPUnit9x1Migration:risky',
            // '@PHPUnit10x0Migration:risky',
            // '@Symfony',
            // '@Symfony:risky',
        ],
        true
    ));
    $fixerFactory = new FixerFactory;
    $fixerFactory->registerBuiltInFixers();
    $fixerFactory->useRuleSet($ruleSet);

    foreach ($fixerFactory->getFixers() as $fixer) {
        $configuration = $ruleSet->getRuleConfiguration($fixer->getName());
        $class = \get_class($fixer);
        null === $configuration ? $ecsConfig->rule($class) : $ecsConfig->ruleWithConfiguration($class, $configuration);
    }
};
