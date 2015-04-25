<?php

namespace horses\action;

use horses\auth\UserIdFactory;
use horses\auth\CredentialsStrategy;

interface AuthenticatingAction
{
    /**
     * @return CredentialsStrategy
     */
    public function getCredentialsFactory();

    /**
     * @return UserIdFactory
     */
    public function getUserIdFactory();
}
