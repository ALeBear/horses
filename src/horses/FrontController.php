<?php

namespace horses;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use horses\action\StatefulActionInterface;
use horses\action\AuthenticatedActionInterface;
use horses\action\ActionInterface;
use horses\config\Collection;
use Exception;

class FrontController
{
    public function run(Request $request, Kernel $kernel)
    {
        try {
            $serverContext = $kernel->getServerContext();
            $configs = $kernel->getConfigCollection();
            /** @var ActionInterface $action */
            $action = $this->route($serverContext, $request, $configs);
            if (!$action) {
                //404
            }

            $credentialsNeeded = $action->getAuthorizationCredentials();
            if (!is_null($credentialsNeeded)) {
                if (!$this->getUser()->hasCredentials($credentialsNeeded)) {
                    //400
                }
            }

            if ($action instanceof StatefulActionInterface) {
                /** @var StatefulActionInterface $action */
                $action->setSession($this->getSession());
            }

            if ($action instanceof AuthenticatedActionInterface) {
                /** @var AuthenticatedActionInterface $action */
                $action->setAuthentication($this->getUser());
            }

            $responder = $action->execute($request);
        } catch (Exception $e) {
            //500
        }

        $responder->output();
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

    protected function getUser()
    {

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
}
