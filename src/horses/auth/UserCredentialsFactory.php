<?php

namespace horses\auth;

interface UserCredentialsFactory
{
    /**
     * @return UserCredentials
     */
    public function getCredentials();
}