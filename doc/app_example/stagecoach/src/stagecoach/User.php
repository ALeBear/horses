<?php

namespace stagecoach;

use horses\auth\Authorization;
use horses\auth\User as AuthUser;
use horses\auth\UserId;

class User implements AuthUser
{
    protected $id;
    protected $username;
    protected $passwordHash;


    /**
     * @return UserId
     */
    public function getUserId()
    {
        return new UserId($this->id);
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
