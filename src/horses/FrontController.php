<?php

namespace horses;

use horses\auth\User;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use horses\action\StatefulAction;
use horses\action\AuthenticatedAction;
use horses\action\Action;
use horses\config\Collection;
use Exception;

class FrontController
{
    /** @var  User */
    protected $user;

    public function run(Request $request, Kernel $kernel)
    {
        try {
            $serverContext = $kernel->getServerContext();
            $configs = $kernel->getConfigCollection();
            /** @var Action $action */
            $action = $this->route($serverContext, $request, $configs);
            if (!$action) {
                //404
            }

            if ($action instanceof StatefulAction) {
                /** @var StatefulAction $action */
                $action->setState($this->getState());
            }

            /** @var Action $action */
            $authorizationsNeeded = $action->getAuthorizationsNeeded();
            if (!is_null($authorizationsNeeded)) {
                $user = $this->getUser($action);
                if (!$user || !$user->hasAuthorizations($authorizationsNeeded)) {
                    //400
                }

            }

            if ($action instanceof AuthenticatedAction && $user) {
                /** @var AuthenticatedAction $action */
                $action->setAuthentication($user);
            }

            $responder = $action->execute($request);
        } catch (Exception $e) {
            //500
        }

        $responder->output();
    }

    /**
     * @return State
     */
    protected function getState()
    {
        $session = new Session();
        $session->start();

        return new State($session);
    }

    /**
     * @param AuthenticatedAction $action
     * @return User|null
     */
    protected function getUser(AuthenticatedAction $action)
    {
        if (!$this->user) {
            $this->user = $this->loadUser($action);
        }

        return $this->user;
    }

    /**
     * @param AuthenticatedAction $action
     * @return User|null
     */
    protected function loadUser(AuthenticatedAction $action)
    {
        switch (true) {
            case $userId = $this->getUserIdFromState($action):
                return $action->getUserFactory()->getUserFromId($userId);
            case $userId = $this->getUserIdFromCredentials($action):
                if ($action instanceof StatefulAction && $userId) {
                    $action->getState()->saveUserId($userId);
                }
                return $action->getUserFactory()->getUserFromId($userId);
            default:
                return null;
        }
    }

    /**
     * @param Action $action
     * @return null|string
     */
    protected function getUserIdFromState(Action $action)
    {
        return $action instanceof StatefulAction
            ? $action->getState()->getUserId()
            : null;
    }

    /**
     * @param AuthenticatedAction $action
     * @return null|string
     */
    protected function getUserIdFromCredentials(AuthenticatedAction $action)
    {
        $credentialsFactory = $action->getCredentialsFactory();
        if ($credentialsFactory) {
            return $action->getAuthenticator()->getUserId($credentialsFactory->getCredentials());
        }

        return null;
    }

    /**
     * @param ServerContext $serverContext
     * @param Request $request
     * @param Collection $configurations
     * @return Action
     */
    protected function route(ServerContext $serverContext, Request $request, Collection $configurations)
    {

    }
}
