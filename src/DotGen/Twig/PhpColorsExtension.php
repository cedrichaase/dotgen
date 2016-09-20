<?php
namespace DotGen\Twig;

use Mexitek\PHPColors\Color;

/**
 * Class PhpColorsExtension
 *
 * @package DotGen\Twig
 */
class PhpColorsExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('darken', [$this, 'darken']),
            new \Twig_SimpleFilter('lighten', [$this, 'lighten']),
            new \Twig_SimpleFilter('saturate', [$this, 'saturate']),
            new \Twig_SimpleFilter('desaturate', [$this, 'desaturate']),
            new \Twig_SimpleFilter('complementary', [$this, 'complementary']),
            new \Twig_SimpleFilter('isLight', [$this, 'isLight']),
            new \Twig_SimpleFilter('isDark', [$this, 'isDark']),
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('randomColor', [$this, 'randomColor']),
        ];
    }

    public function darken($colorString, $amount = 10)
    {
        $color = new Color($colorString);
        return $color->darken($amount);
    }

    public function lighten($colorString, $amount = 10)
    {
        $color = new Color($colorString);
        return $color->lighten($amount);
    }

    public function complementary($colorString)
    {
        $color = new Color($colorString);
        return $color->complementary();
    }

    public function saturate($colorString, $amount = 10)
    {
        $hsl = Color::hexToHsl($colorString);
        $saturation = $hsl['S'];
        $hsl['S'] = $saturation + min($saturation * $amount / 100, 1);
        return Color::hslToHex($hsl);
    }

    public function desaturate($colorString, $amount = 10)
    {
        $hsl = Color::hexToHsl($colorString);
        $saturation = $hsl['S'];
        $hsl['S'] = $saturation - min($saturation * $amount / 100, 1);
        return Color::hslToHex($hsl);
    }

    public function randomColor()
    {
        return bin2hex(openssl_random_pseudo_bytes(3));
    }

    public function isDark($colorString)
    {
        $color = new Color($colorString);
        return $color->isDark();
    }

    public function isLight($colorString)
    {
        $color = new Color($colorString);
        return $color->isLight();
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'phpcolors';
    }
}