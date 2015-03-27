<?php

namespace horses\auth;

interface UserIdFactory
{
    /**
     * @param Credentials|null $credentials
     * @return UserId|null
     */
    public function getUserId(Credentials $credentials = null);
}