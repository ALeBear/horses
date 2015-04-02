<?php

namespace stagecoach\action;

use horses\action\Action;
use horses\action\AuthenticatedAction;
use horses\action\AuthenticatingAction;
use horses\action\StatefulAction;
use horses\auth\Authorization;
use horses\auth\CredentialsFactory;
use horses\auth\User;
use horses\auth\UserFactory;
use horses\auth\UserIdFactory;
use horses\responder\Redirect;
use horses\State;
use horses\Request;
use stagecoach\DummyUserFactory;
use stagecoach\DummyUserIdFactory;
use stagecoach\PostCredentialsFactory;
use stagecoach\responder\LoginResponder;
use horses\Router;

class Login implements Action, StatefulAction, AuthenticatingAction, AuthenticatedAction
{
    /** @var  State */
    protected $state;


    /** @inheritdoc */
    public function setState(State $state)
    {
        $this->state = $state;
    }

    /** @inheritdoc */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return CredentialsFactory
     */
    public function getCredentialsFactory()
    {
        return new PostCredentialsFactory();
    }

    /**
     * @return UserIdFactory
     */
    public function getUserIdFactory()
    {
        return new DummyUserIdFactory();
    }

    /**
     * @return Authorization
     */
    public function getAuthorizationNeeded()
    {
        return null;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setAuthentication(User $user = null)
    {
    }

    /**
     * @return UserFactory
     */
    public function getUserFactory()
    {
        return new DummyUserFactory();
    }

    /** @inheritdoc */
    public function execute(Request $request, Router $router)
    {
        if ($this->getState()->getUserId()) {
            return new Redirect($router->getUrlFromAction(NeedLogin::class));
        }

        $message = '';
        if ($request->isMethod(Request::METHOD_POST)) {
            $message = 'Wrong credentials';
        }
        $username = $request->getPostParam('username');
        $password = $request->getPostParam('username');

        $responder = new LoginResponder();
        $responder->setCredentials($username, $password);
        $responder->setMessage($message);

        return $responder;
    }
}
