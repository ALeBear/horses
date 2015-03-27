<?php

namespace horses\auth;

interface UserIdFactory
{
    /**
     * @param UserCredentials $credentials
     * @return UserId
     */
    public function getUserId(UserCredentials $credentials);
}