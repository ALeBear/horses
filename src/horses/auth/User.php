<?php

namespace horses\auth;

interface User
{
    /**
     * @return UserId
     */
    public function getUserId();

    /**
     * @param Authorization $authorization
     * @return boolean
     */
    public function hasAuthorization(Authorization $authorization);
}