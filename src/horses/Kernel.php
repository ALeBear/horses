<?php

namespace horses;

use horses\auth\Authenticator;
use Symfony\Component\Config\FileLocator;
use horses\config\YamlFileLoader;
use horses\config\Collection as ConfigCollection;
use horses\config\Factory;

/**
 * This kernel uses yaml config files read by horses\config\Config classes
 */
class Kernel
{
    const DEFAULT_ENV = 'prod';

    /** @var  ServerContext */
    protected $serverContext;
    /** @var  ConfigCollection */
    protected $configCollection;

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
     * @return ConfigCollection
     * @throws KernelPanicException If configs dir cannot be read
     */
    public function getConfigCollection()
    {
        if (!$this->configCollection) {
            $configDir = $this->serverContext->getPath(ServerContext::DIR_CONFIG);
            if (!is_dir($configDir)) {
                throw new KernelPanicException(sprintf('Config dir does not exists or not readable: %s', $configDir));
            }

            $loader = new YamlFileLoader(new FileLocator([$configDir, $configDir . '/' . $this->serverContext->getEnvironment()]));
            $this->configCollection = new ConfigCollection(new Factory($loader, \horses\config\Config::class));
        }

        return $this->configCollection;
    }

    /**
     * @return ServerContext
     */
    public function getServerContext()
    {
        return $this->serverContext;
    }

    /**
     * @return Authenticator
     */
    public function getAuthenticator()
    {
        return new Authenticator();
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return new Router($this->getServerContext(), $this->getConfigCollection());
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
        $context->set(ServerContext::DIR_SRC, $context->getPath(ServerContext::DIR_ROOT) . '/src');
        $context->set(ServerContext::DIR_ACTIONS, $context->getPath(ServerContext::DIR_ROOT) . '/application');
        $context->set(ServerContext::DIR_CONFIG, $context->getPath(ServerContext::DIR_ROOT) . '/config');
        $context->set(ServerContext::DIR_PUBLIC, $context->getPath(ServerContext::DIR_ROOT) . '/public');

        return $context;
    }
}
