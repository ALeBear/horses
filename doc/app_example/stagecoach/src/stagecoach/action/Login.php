<?php

namespace stagecoach\action;

use Doctrine\ORM\EntityManager;
use horses\action\Action;
use horses\action\AuthenticatedAction;
use horses\action\AuthenticatingAction;
use horses\action\DoctrineAwareAction;
use horses\action\StatefulAction;
use horses\auth\User;
use horses\responder\Redirect;
use horses\State;
use horses\Request;
use stagecoach\DummyUserIdFactory;
use stagecoach\PostCredentialsFactory;
use stagecoach\responder\LoginResponder;
use horses\Router;
use stagecoach\UserFactory;

class Login implements Action, StatefulAction, AuthenticatingAction, AuthenticatedAction, DoctrineAwareAction
{
    /** @var  State */
    protected $state;
    /** @var  EntityManager */
    protected $entityManager;


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

    /** @inheritdoc */
    public function getCredentialsFactory()
    {
        return new PostCredentialsFactory();
    }

    /** @inheritdoc */
    public function getUserIdFactory()
    {
        return new DummyUserIdFactory();
    }

    /** @inheritdoc */
    public function getAuthorizationNeeded()
    {
        return null;
    }

    /** @inheritdoc */
    public function setAuthentication(User $user = null)
    {
    }

    /** @inheritdoc */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /** @inheritdoc */
    public function getUserFactory()
    {
        return new UserFactory($this->entityManager);
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
