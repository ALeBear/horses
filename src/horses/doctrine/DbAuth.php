<?php

namespace horses\plugin\doctrine;

use Doctrine\ORM\EntityManager;
use horses\auth\AuthAbstract;

/**
 * A Auth subclass, storing the user in DB through Doctrine
 */
class DbAuthAbstract extends AuthAbstract
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    
    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @return \horses\plugin\doctrine\DbAuthAbstract
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
     * @return \horses\auth\AbstractUser
     */
    public function getUser($email, $passwordHash)
    {
        return $this->em->getRepository($this->userClassname)->findOneBy(['email' => $email, 'passwordHash' => $passwordHash]);
    }
}