<?php
namespace DotGen\Api;

use DotGen\Config\Resource\Converter\ArrayToResourceConverter;
use DotGen\Config\Resource\InheritanceHandler\InheritanceHandler;
use DotGen\Config\Resource\IResource;
use DotGen\Config\Resource\Parser\ParserException;
use DotGen\Config\Resource\Parser\ParserManager;
use DotGen\Config\Resource\Repository\File\FileResourceRepository;
use DotGen\Config\Validator\ResourceValidationException;
use DotGen\Config\Validator\ResourceValidator;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class FileSystemInput
 *
 * @package DotGen\Api
 */
class FileSystemInput
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ParserManager
     */
    private $parser;

    /**
     * @var ArrayToResourceConverter
     */
    private $converter;

    /**
     * @var InheritanceHandler
     */
    private $inheritor;

    /**
     * @var ResourceValidator
     */
    private $validator;

    /**
     * FileSystemInput constructor.
     */
    public function __construct()
    {
        $this->logger = new NullLogger();
        $this->parser = new ParserManager();
        $this->converter = new ArrayToResourceConverter();
        $this->inheritor = new InheritanceHandler();
        $this->validator = new ResourceValidator();
    }

    /**
     * @param string $path
     * @param array $includes
     *
     * @return IResource
     * @throws ApiException
     */
    public function process(string $path, array $includes)
    {
        $config = file_get_contents($path);

        # region parse
        try {
            $parsed = $this->parser->parse($config);
        } catch(ParserException $e) {
            $this->logger->critical('Oops, unfortunately we really could not parse this file', [
                'file' => $path,
                'message' => $e->getMessage(),
                'content' => $config,
            ]);
            
            throw new ApiException(ApiException::MSG_DEFAULT);
        }
        # endregion

        # region convert to resource
        $resource = $this->converter->convert($parsed);
        # endregion

        # region handle inheritance
        $startInheritance = microtime(true);
        foreach($includes as $include)
        {
            $repo = new FileResourceRepository($include);
            $this->inheritor->registerRepository($repo);
        }
        $resource = $this->inheritor->extend($resource);

        $this->logger->info('Done handling inheritance', [
            'time' => microtime(true) - $startInheritance,
        ]);
        # endregion

        # region validate
        try {
            $this->validator->validate($resource);
        } catch(ResourceValidationException $e) {
            $this->logger->critical('Oops, something seems to be wrong with your configuration', [
                'resource' => $resource->getName(),
                'message' => $e->getMessage(),
            ]);

            throw new ApiException(ApiException::MSG_DEFAULT);
        }
        # endregion

        return $resource;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->converter->setLogger($this->logger);
    }
}