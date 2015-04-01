<?php

namespace stagecoach;

use horses\auth\User;
use horses\auth\UserFactory;
use horses\auth\UserId;

class DummyUserFactory implements UserFactory
{
    /**
     * @param UserId $userId
     * @return User
     */
    public function getUserFromId(UserId $userId)
    {
        return new DummyUser($userId);
    }

}
