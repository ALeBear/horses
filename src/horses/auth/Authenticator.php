<?php

namespace horses\auth;

interface Authenticator
{
    /**
     * @param UserCredentials $credentials
     * @return string
     */
    public function getUserId(UserCredentials $credentials);
}