<?php

namespace horses\auth;

use horses\Request;

interface CredentialsStrategy
{
    /**
     * @param Request $request
     * @return Credentials
     */
    public function getCredentials(Request $request);
}
