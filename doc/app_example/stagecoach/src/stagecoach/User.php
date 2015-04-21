<?php

namespace stagecoach;

use horses\auth\Authorization;
use horses\auth\User as AuthUser;
use horses\auth\UserId;

/**
 * @Entity
 */
class User implements AuthUser
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     * @param integer
     */
    protected $id;

    /**
     * @Column(type="string", length=15)
     * @var string
     */
    protected $username;

    /**
     * @Column(type="string", length=255)
     * @var string
     */
    protected $passwordHash;


    /**
     * @return UserId
     */
    public function getUserId()
    {
        return new UserId($this->id);
    }

    /**
     * @param $password
     * @return bool
     */
    public function isPasswordValid($password)
    {
        return password_verify($password, $this->passwordHash);
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
