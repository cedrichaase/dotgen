<?php
namespace cedrichaase\DotGen\Console;

use cedrichaase\DotGen\Console\Command\RenderCommand;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;

/**
 * Class Application
 *
 * @package cedrichaase\DotGen\Console
 */
class Application extends BaseApplication
{
    /**
     * Application constructor.
     */
    public function __construct()
    {
        parent::__construct('dotgen', '0.1.1');

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