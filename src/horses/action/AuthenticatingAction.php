<?php

namespace horses\action;

use horses\auth\UserIdFactory;
use horses\auth\CredentialsFactory;

interface AuthenticatingAction
{
    /**
     * @return CredentialsFactory
     */
    public function getCredentialsFactory();

    /**
     * @return UserIdFactory
     */
    public function getUserIdFactory();
}
