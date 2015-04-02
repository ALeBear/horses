<?php

namespace stagecoach\action;

use horses\action\Action;
use horses\action\AuthenticatedAction;
use horses\action\StatefulAction;
use horses\auth\Authorization;
use horses\auth\User;
use horses\auth\UserFactory;
use horses\State;
use horses\Request;
use stagecoach\DummyAuthorization;
use stagecoach\DummyUserFactory;
use stagecoach\responder\String;
use horses\Router;

class NeedLogin implements Action, StatefulAction, AuthenticatedAction
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
     * @return Authorization
     */
    public function getAuthorizationNeeded()
    {
        return new DummyAuthorization();
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
        return new String('Congratulations! you are logged in.');
    }

}
