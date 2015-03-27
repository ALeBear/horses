<?php

namespace horses\auth;

interface UserFactory
{
    /**
     * @param UserId $userId
     * @return User
     */
    public function getUserFromId(UserId $userId);
}