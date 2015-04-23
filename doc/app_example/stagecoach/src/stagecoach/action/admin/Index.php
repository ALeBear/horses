<?php

namespace stagecoach\action\admin;

use Doctrine\ORM\EntityManager;
use horses\action\Action;
use horses\action\DoctrineAwareAction;
use horses\auth\User;
use horses\doctrine\SimpleAccessGrantsFactory;
use horses\doctrine\SimpleAccessPolicy;
use horses\doctrine\UserFactory;
use horses\Request;
use horses\Router;
use horses\action\StatefulAction;
use horses\action\AuthenticatedAction;
use horses\State;
use horses\doctrine\User as DoctrineUser;
use stagecoach\responder\admin\IndexResponder;

class Index implements Action, StatefulAction, AuthenticatedAction, DoctrineAwareAction
{
    /** @var State */
    protected $state;
    /** @var EntityManager */
    protected $entityManager;
    /** @var DoctrineUser */
    protected $user;


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
    public function getAccessPolicy()
    {
        return new SimpleAccessPolicy(['admin']);
    }

    /** @inheritdoc */
    public function setAuthentication(User $user = null)
    {
        $this->user = $user;
    }

    /** @inheritdoc */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @return UserFactory
     */
    public function getUserFactory()
    {
        return new UserFactory(
            $this->entityManager->getRepository('horses\doctrine\User'),
            new SimpleAccessGrantsFactory($this->entityManager->getRepository('horses\doctrine\SimpleAccessCode')));
    }

    /** @inheritdoc */
    public function execute(Request $request, Router $router)
    {
        $responder = new IndexResponder("Bienvenue dans la section d'administration.");
        $responder->setUsername($this->user->__toString());

        return $responder;
    }
}