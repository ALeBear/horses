<?php

namespace horses\action;

use horses\auth\UserIdFactory;
use horses\auth\UserCredentialsFactory;

interface AuthenticatingAction
{
    /**
     * @return UserCredentialsFactory
     */
    public function getCredentialsFactory();

    /**
     * @return UserIdFactory
     */
    public function getAuthenticator();
}
