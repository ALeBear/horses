<?php

namespace horses\auth;

interface UserFactory
{
    /**
     * @param string $userId
     * @return User
     */
    public function getUserFromId($userId);
}