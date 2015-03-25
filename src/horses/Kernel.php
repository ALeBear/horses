<?php

namespace horses;

use Symfony\Component\Config\FileLocator;
use horses\config\YamlFileLoader;
use horses\config\Collection;
use horses\config\Factory;

/**
 * This kernel uses yaml config files read by horses\config\Config classes
 */
class Kernel
{
    const DEFAULT_ENV = 'prod';

    /** @var  ServerContext */
    protected $serverContext;

    /**
     * @param $projectRootPath
     * @param $environment
     * @param ServerContext $emptyContext
     */
    public function __construct($projectRootPath, $environment, ServerContext $emptyContext)
    {
        $this->serverContext = $this->buildContext($emptyContext, $environment, $projectRootPath);
    }

    /**
     * @return Collection
     * @throws KernelPanicException If configs dir cannot be read
     */
    public function getConfigCollection()
    {
        $configDir = $this->serverContext->getPath(ServerContext::DIR_CONFIG);
        if (!is_dir($configDir)) {
            throw new KernelPanicException(sprintf('Config dir does not exists or not readable: %s', $configDir));
        }

        $loader = new YamlFileLoader(new FileLocator([$configDir, $configDir . '/' . $this->serverContext->getEnvironment()]));
        return new Collection(new Factory($loader, \horses\config\Config::class));
    }

    /**
     * @return ServerContext
     */
    public function getServerContext()
    {
        return $this->serverContext;
    }

    /**
     * @param ServerContext $context
     * @param $environment
     * @param $projectRootPath
     * @return ServerContext
     */
    protected function buildContext(ServerContext $context, $environment, $projectRootPath)
    {
        $context->set('ENV', $environment);
        $context->set(ServerContext::DIR_ROOT, $projectRootPath);
        $context->set(ServerContext::DIR_APPLICATION, $context->getPath(ServerContext::DIR_ROOT) . '/application');
        $context->set(ServerContext::DIR_LIB, $context->getPath(ServerContext::DIR_ROOT) . '/lib');
        $context->set(ServerContext::DIR_CONTROLLERS, $context->getPath(ServerContext::DIR_APPLICATION) . '/controller');
        $context->set(ServerContext::DIR_CONFIG, $context->getPath(ServerContext::DIR_APPLICATION) . '/config');
        $context->set(ServerContext::DIR_PUBLIC, $context->getPath(ServerContext::DIR_ROOT) . '/public');

        return $context;
    }
}
