<?php
namespace DotGen\Generator;

interface IGeneratorResource
{
    /**
     * @return mixed
     */
    public function getTemplatePath();

    /**
     * @return mixed
     */
    public function getCollections();
}