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

namespace Guanguans\PhpCsFixerCustomFixers\Support;

use Composer\Script\Event;
use Rector\Config\RectorConfig;
use Rector\DependencyInjection\LazyContainerFactory;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @internal
 *
 * @property \Symfony\Component\Console\Output\ConsoleOutput $output
 *
 * @method void configureIO(InputInterface $input, OutputInterface $output)
 */
final class ComposerScripts
{
    public static function makeRectorConfig(): RectorConfig
    {
        static $rectorConfig;

        return $rectorConfig ??= (new LazyContainerFactory)->create();
    }

    /**
     * @see \Rector\Console\Style\SymfonyStyleFactory
     */
    public static function makeSymfonyStyle(): SymfonyStyle
    {
        static $symfonyStyle;

        if ($symfonyStyle instanceof SymfonyStyle) {
            return $symfonyStyle;
        }

        // to prevent missing argv indexes
        if (!isset($_SERVER['argv'])) {
            $_SERVER['argv'] = [];
        }

        $argvInput = new ArgvInput;
        $consoleOutput = new ConsoleOutput;

        // to configure all -v, -vv, -vvv options without memory-lock to Application run() arguments
        (fn () => $this->configureIO($argvInput, $consoleOutput))->call(new Application);

        // --debug is called
        if ($argvInput->hasParameterOption(['--debug', '--xdebug'])) {
            $consoleOutput->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
        }

        return $symfonyStyle = new SymfonyStyle($argvInput, $consoleOutput);
    }

    public static function requireAutoload(Event $event): void
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';

        (function (): void {
            $this->output->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
        })->call($event->getIO());
    }
}
