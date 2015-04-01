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
use horses\State;
use horses\Request;
use stagecoach\DummyAuthorization;
use stagecoach\DummyUserFactory;
use stagecoach\DummyUserIdFactory;
use stagecoach\GetCredentialsFactory;
use stagecoach\responder\StringResponder;

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
        return new GetCredentialsFactory();
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
    public function execute(Request $request)
    {
        return new StringResponder('Logged in! Try changing the get parameters.');
    }

}
