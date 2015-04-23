<?php

namespace horses\doctrine;

use horses\auth\AccessGrantsFactory;
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
     * @Column(type="string", length=100)
     * @var string
     */
    protected $name;

    /**
     * @Column(type="string", length=255)
     * @var string
     */
    protected $passwordHash;

    /** @var AccessGrantsFactory */
    protected $accessGrantsFactory;


    /**
     * @param AccessGrantsFactory $grantsFactory
     * @return $this
     */
    public function setGrantsFactory(AccessGrantsFactory $grantsFactory)
    {
        $this->accessGrantsFactory = $grantsFactory;
        return $this;
    }

    /** @inheritdoc */
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

    /** @inheritdoc */
    public function getAccessGrants()
    {
        return $this->accessGrantsFactory->getGrantsForUser($this);
    }

    /** @inheritdoc */
    public function __toString()
    {
        return $this->name;
    }
}
