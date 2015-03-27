<?php

namespace horses\auth;

interface CredentialsFactory
{
    /**
     * @return Credentials
     */
    public function getCredentials();
}