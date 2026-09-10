<?php

/** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpUndefinedNamespaceInspection */
/** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

use Ergebnis\License\Holder;
use Ergebnis\License\Range;
use Ergebnis\License\Type\MIT;
use Ergebnis\License\Url;
use Ergebnis\License\Year;
use Guanguans\PhpCsFixerCustomFixers\Set\SetList;
use PhpCsFixer\Finder as PhpCsFixerFinder;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use Symfony\Component\Finder\Finder;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    // ->withoutParallel()
    ->withPaths(array_keys(iterator_to_array(
        PhpCsFixerFinder::create()
            ->in(getcwd())
            ->exclude([
                'Fixtures/',
                'vendor-bin/',
            ])
            ->notPath([
                // '/lang\/.*\.json$/',
            ])
            ->notName([
                '/\.blade\.php$/',
            ])
            ->ignoreDotFiles(false)->ignoreUnreadableDirs(false)->ignoreVCS(true)->ignoreVCSIgnored(true)
            ->append(
                Finder::create()->files()->in(getcwd())->depth(0)
                    ->ignoreDotFiles(false)->ignoreUnreadableDirs(false)->ignoreVCS(true)->ignoreVCSIgnored(true)
                    ->filter(static fn (SplFileInfo $file): bool => str_starts_with(
                        $file->getContents(),
                        '#!/usr/bin/env php'
                    ))
            )
    )))
    ->withSkip([])
    ->withSets([SetList::GUANGUANS])
    ->withConfiguredRule(HeaderCommentFixer::class, [
        'comment_type' => 'PHPDoc',
        'header' => (static function (): string {
            $mit = MIT::text(
                __DIR__.'/LICENSE',
                Range::since(
                    Year::fromString('2025'),
                    new DateTimeZone('Asia/Shanghai'),
                ),
                Holder::fromString('guanguans<ityaozm@gmail.com>'),
                Url::fromString('https://github.com/guanguans/php-cs-fixer-custom-fixers'),
            );

            $mit->save();

            return trim($mit->header());
        })(),
        'location' => 'after_declare_strict',
        'separate' => 'both',
    ]);
