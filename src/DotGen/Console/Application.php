<?php
namespace DotGen\Console;

use DotGen\Console\Command\RenderCommand;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;

/**
 * Class Application
 *
 * @package cedrichaase\DotGen\Console
 */
class Application extends BaseApplication
{
    const APP_NAME = 'dotgen';

    const APP_VERSION = '0.4.0';

    /**
     * Application constructor.
     */
    public function __construct()
    {
        parent::__construct(self::APP_NAME, self::APP_VERSION);

        $this->addCommands($this->commands());
    }

    /**
     * @return Command[]
     */
    private function commands()
    {
        return [
            new RenderCommand(),
        ];
    }
}