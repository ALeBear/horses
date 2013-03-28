<?php

namespace horses\plugin\auth;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class Auth
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;
    
    /**
     * @var string
     */
    protected $userClassname;
    
    
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
     * Fully qualified classname
     * @param string $name
     * @return \horses\plugin\auth\Auth
     */
    public function injectUserClassname($name)
    {
        $this->userClassname = $name;
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
    
    /**
     * Hash a password
     * @param string $password
     * @return string
     */
    public function getPasswordHash($password)
    {
        return sha1($password);
    }
    
    /**
     * @param \Symfony\Component\HttpFoundation\Session $session
     * @return \horses\plugin\auth\AbstractUser
     */
    public function getUserFromSession(Session $session)
    {
        if (!$session->get('email') || !$session->get('passwordHash')) {
            $user = null;
        } else {
            $user = $this->getUser($session->get('email'), $session->get('passwordHash'));
        }
        
        return $user;
    }
    
    /**
     * @param \horses\plugin\auth\AbstractUser $user
     * @param \Symfony\Component\HttpFoundation\Session $session
     * @return \horses\plugin\auth\Auth $this
     */
    public function saveUserToSession(AbstractUser $user, Session $session)
    {
        $session->set('email', $user->getEmail());
        $session->set('passwordHash', $user->getPasswordHash());
        
        return $this;
    }
    
    /**
     * @param \Symfony\Component\HttpFoundation\Session $session
     * @return \horses\plugin\auth\Auth $this
     */
    public function removeUserFromSession(Session $session)
    {
        $session->set('email', null);
        $session->set('passwordHash', null);
        
        return $this;
    }
}