<?php

namespace horses\action;

use horses\auth\Authenticator;
use horses\auth\User;
use horses\auth\UserCredentialsFactory;
use horses\auth\UserFactory;

interface AuthenticatedAction
{
    /**
     * @return UserCredentialsFactory|null
     */
    public function getCredentialsFactory();

    /**
     * @return Authenticator
     */
    public function getAuthenticator();

    /**
     * @return UserFactory
     */
    public function getUserFactory();

    /**
     * @param User $user
     * @return mixed
     */
    public function setAuthentication(User $user);
}
