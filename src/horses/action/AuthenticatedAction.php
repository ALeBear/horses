<?php

namespace horses\action;

use horses\auth\Authorization;
use horses\auth\User;
use horses\auth\UserFactory;


interface AuthenticatedAction
{
    /**
     * @return Authorization
     */
    public function getAuthorizationNeeded();

    /**
     * @param User $user
     * @return $this
     */
    public function setAuthentication(User $user = null);

    /**
     * @return UserFactory
     */
    public function getUserFactory();
}
