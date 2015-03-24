<?php

namespace horses;

use horses\config\Collection;
use horses\config\Factory;
use horses\config\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Config\FileLocator;
use Exception;

class FrontController
{
    /**
     * @var string The name of the default environment
     */
    const DEFAULT_ENV = 'prod';


    public function run($projectRootPath)
    {
        try {
            $request = $this->getRequest();
            $serverContext = $this->getServerContext($request, $projectRootPath);
            $configs = $this->getConfigCollection($serverContext->getPath(ServerContext::DIR_CONFIG), $serverContext->getEnvironment());
            $action = $this->route($serverContext, $request, $configs);
            if (!$action) {
                //404
            }

//            if ($action->needsSession()) {
//                $this->getSession();
//            }
//            if ($action->needsAuthentication()) {
//                //Authenticate
//            }
//            if ($action->needsAuthorization()) {
//                //Authorize
//            }
            //Load plugins (the ones requested by action?)
            //Manage content nego and loads responder
            //Give control to action
            //Make the Responder output data and return

        } catch (Exception $e) {
            //500
        }
    }

    /**
     * @param Request $request
     * @param string $projectRootPath
     * @return ServerContext
     */
    protected function getServerContext(Request $request, $projectRootPath)
    {
        $serverContext = new ServerContext();
        $serverContext->set('ENV', $request->server->get('ENV', static::DEFAULT_ENV));
        $serverContext->set(ServerContext::DIR_ROOT, $projectRootPath);
        $serverContext->set(ServerContext::DIR_APPLICATION, $serverContext->getPath(ServerContext::DIR_ROOT) . '/application');
        $serverContext->set(ServerContext::DIR_LIB, $serverContext->getPath(ServerContext::DIR_ROOT) . '/lib');
        $serverContext->set(ServerContext::DIR_CONTROLLERS, $serverContext->getPath(ServerContext::DIR_APPLICATION) . '/controller');
        $serverContext->set(ServerContext::DIR_CONFIG, $serverContext->getPath(ServerContext::DIR_APPLICATION) . '/config');
        $serverContext->set(ServerContext::DIR_PUBLIC, $serverContext->getPath(ServerContext::DIR_ROOT) . '/public');

        return $serverContext;
    }

    protected function getConfigCollection($configDir, $env)
    {
        if (!is_dir($configDir)) {
            throw new KernelPanicException(sprintf('Config dir does not exists or not readable: %s', $configDir));
        }

        $loader = new YamlFileLoader(new FileLocator([$configDir, $configDir . '/' . $env]));
        return new Collection(new Factory($loader, 'horses\config\Config'));
    }

    /**
     * @return Session
     */
    protected function getSession()
    {
        $session = new Session();
        $session->start();

        return $session;
    }

    /**
     * @param ServerContext $serverContext
     * @param Request $request
     * @param Collection $configurations
     * @return ActionInterface
     */
    protected function route(ServerContext $serverContext, Request $request, Collection $configurations)
    {

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        return Request::createFromGlobals();
    }
}
