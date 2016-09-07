<?php
namespace cedrichaase\DotGen\DotfileGenerator;

/**
 * Class DotfileGenerator
 *
 * @package cedrichaase\DotGen\DotfileGenerator
 */
class DotfileGenerator
{
    const BASE_DIR = __DIR__ . '/../../../..';

    const DOTFILES_DIR = self::BASE_DIR . '/dotfiles';

    const CONFIG_PATH = self::BASE_DIR . '/config/config.ini';

    private $twig;
    
    public function renderDotfiles()
    {
        $config = parse_ini_file(self::CONFIG_PATH, true);
        
        var_dump($config);
        
        $data = $config['i3config'];
        $dotfile = $this->twig()->render('.i3/config.twig', $data);
        file_put_contents(self::DOTFILES_DIR . '/.i3config', $dotfile);
        
        $data = $config['i3status'];
        $dotfile = $this->twig()->render('.config/i3status/config.twig', $data);
        var_dump($dotfile);
        file_put_contents(self::DOTFILES_DIR . '/.config/i3status/config', $dotfile);
    }

    /**
     * @return TwigController
     */
    private function twig()
    {
        if(!$this->twig)
        {
            $this->twig = new TwigController();
        }
        
        return $this->twig;
    }
}