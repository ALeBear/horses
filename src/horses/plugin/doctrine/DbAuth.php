<?php

namespace horses\plugin\auth;

use Doctrine\ORM\EntityManager;
use horses\plugin\auth\AbstractAuth;

/**
 * A Auth subclass, storing the user in DB through Doctrine
 */
class DbAuth extends AbstractAuth
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;
    
    
    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @return \horses\plugin\auth\Auth
     */
    public function injectEntityManager(EntityManager $em)
    {
        $this->em = $em;
        return $this;
    }
    
    /**
     * Authenticate a user and returns it
     * @param string $email
     * @param string $passwordHash
     * @return \horses\plugin\auth\AbstractUser
     */
    public function getUser($email, $passwordHash)
    {
        return $this->em->getRepository($this->userClassname)->findOneBy(array('email' => $email, 'passwordHash' => $passwordHash));
    }
}