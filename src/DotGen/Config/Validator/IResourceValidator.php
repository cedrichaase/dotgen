<?php
namespace DotGen\Config\Validator;

use DotGen\Config\IResource;

interface IResourceValidator
{
    /**
     * @param IResource $resource
     * 
     * @throws ResourceValidationException
     */
    public static function validate(IResource $resource);
}