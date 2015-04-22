<?php

namespace horses\action;

use horses\auth\AccessPolicy;
use horses\auth\User;
use horses\auth\UserFactory;


interface AuthenticatedAction
{
    /**
     * @return AccessPolicy
     */
    public function getAccessPolicy();

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
