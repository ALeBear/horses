<?php

namespace horses\auth;

use horses\Request;

interface CredentialsFactory
{
    /**
     * @param Request $request
     * @return Credentials
     */
    public function getCredentials(Request $request);
}
