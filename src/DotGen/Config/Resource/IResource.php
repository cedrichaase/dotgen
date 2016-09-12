<?php
namespace DotGen\Config\Resource;
use DotGen\Config\Entity\Collection;

/**
 * Interface IResource
 * 
 * @package DotGen\Config
 */
interface IResource
{
    /**
     * Return the collections managed by this resource
     *
     * @return Collection[]
     */
    public function getCollections();

    /**
     * Returns relative path of the template file
     * for given template name
     *
     * @param $template
     * @return string
     */
    public function getTemplatePath($template);
}