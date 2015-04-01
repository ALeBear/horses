<?php

namespace stagecoach;

use horses\auth\Authorization;
use horses\auth\User;
use horses\auth\UserId;

class DummyUser implements User
{
    /** @var  UserId */
    protected $userId;


    /**
     * @param UserId $userId
     */
    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return UserId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param Authorization $authorization
     * @return boolean
     */
    public function hasAuthorization(Authorization $authorization)
    {
        return true;
    }

}
