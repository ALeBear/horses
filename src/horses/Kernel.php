<?php

namespace horses;

use horses\auth\Authenticator;
use horses\doctrine\EntityManagerFactory;
use horses\i18n\I18nFactory;
use horses\doctrine\ProxiesNotWritableException;
use horses\i18n\Translator;
use horses\config\Config;
use Symfony\Component\Config\FileLocator;
use horses\config\YamlFileLoader;
use horses\config\Collection as ConfigCollection;
use horses\config\Factory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;

class Kernel
{
    const DEFAULT_ENV = 'prod';
    const CONFIG_SECTION = 'kernel';
    const CONFIG_KEY_APPLICATION = 'application';

    /** @var  ServerContext */
    protected $serverContext;
    /** @var  ExceptionHandler */
    protected $exceptionHandler;
    /** @var  ConfigCollection */
    protected $configCollection;
    /** @var  Router */
    protected $router;

    /**
     * @param $projectRootPath
     * @param $environment
     * @param ServerContext $emptyContext
     * @param ExceptionHandler $exceptionHandler
     */
    public function __construct($projectRootPath, $environment, ServerContext $emptyContext, ExceptionHandler $exceptionHandler)
    {
        $this->serverContext = $this->buildContext($emptyContext, $environment, $projectRootPath);
        $this->serverContext->set('APP', $this->getConfigCollection()->getSection(self::CONFIG_SECTION)->get(self::CONFIG_KEY_APPLICATION));
        $this->exceptionHandler = $exceptionHandler
            ->setDisplayErrorDetailFlag($this->serverContext->isProductionEnvironment())
            ->setRouter($this->getRouter());
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
            $this->configCollection = new ConfigCollection(new Factory($loader, Config::class));
            $this->configCollection->load(self::CONFIG_SECTION);
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
     * @return ExceptionHandler
     */
    public function getExceptionHandler()
    {
        return $this->exceptionHandler;
    }

    /**
     * @return Authenticator
     */
    public function getAuthenticator()
    {
        return new Authenticator();
    }

    /**
     * @param Request $request
     * @return Translator
     */
    public function getTranslator(Request $request)
    {
        return (new I18nFactory($this->configCollection, $this->serverContext))->getTranslator($request);
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        if (!$this->router) {
            $this->router = new Router($this->getServerContext(), $this->getConfigCollection());
        }

        return $this->router;
    }

    /**
     * @return EntityManager
     * @throws KernelPanicException If temp dir not writable of some doctrine exception happens
     * @codeCoverageIgnore
     */
    public function getEntityManager()
    {
        try {
            return (new EntityManagerFactory($this->getConfigCollection(), $this->getServerContext()))->getEntityManager();
        } catch (ProxiesNotWritableException $e) {
            throw new KernelPanicException(sprintf("Temp dir not writable: %s", $this->serverContext->getPath(ServerContext::DIR_TMP)));
        } catch (ORMException $e) {
            throw new KernelPanicException($e->getMessage(), $e->getCode(), $e);
        }
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
        $context->set(ServerContext::DIR_ACTIONS, $context->getPath(ServerContext::DIR_ROOT) . '/action');
        $context->set(ServerContext::DIR_CONFIG, $context->getPath(ServerContext::DIR_ROOT) . '/config');
        $context->set(ServerContext::DIR_PUBLIC, $context->getPath(ServerContext::DIR_ROOT) . '/public');
        $context->set(ServerContext::DIR_TMP, $context->getPath(ServerContext::DIR_ROOT) . '/tmp');

        return $context;
    }
}
