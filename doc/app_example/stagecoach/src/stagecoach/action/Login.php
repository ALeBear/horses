<?php

namespace stagecoach\action;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use horses\action\Action;
use horses\action\AuthenticatedAction;
use horses\action\AuthenticatingAction;
use horses\action\DoctrineAwareAction;
use horses\action\StatefulAction;
use horses\auth\NoRestrictionAccessPolicy;
use horses\auth\User;
use horses\doctrine\SimpleAccessGrantsFactory;
use horses\responder\Redirect;
use horses\State;
use horses\Request;
use horses\doctrine\UserIdFactory;
use horses\doctrine\UserFactory;
use stagecoach\action\admin\Index;
use stagecoach\PostCredentialsFactory;
use stagecoach\responder\LoginResponder;
use horses\Router;

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
        return new UserIdFactory($this->getUserEntityRepository());
    }

    /** @inheritdoc */
    public function getAccessPolicy()
    {
        return new NoRestrictionAccessPolicy();
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
        return new UserFactory($this->getUserEntityRepository(), new SimpleAccessGrantsFactory($this->entityManager->getRepository('horses\doctrine\SimpleAccessCode')));
    }

    /** @inheritdoc */
    public function execute(Request $request, Router $router)
    {
        if ($this->getState()->getUserId()) {
            return new Redirect($router->getUrlFromAction(Index::class));
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

    /**
     * @return EntityRepository
     */
    protected function getUserEntityRepository()
    {
        return $this->entityManager->getRepository('horses\doctrine\User');
    }
}
