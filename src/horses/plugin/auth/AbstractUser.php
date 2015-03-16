<?php

namespace horses\plugin\auth;

/**
 * Users superclass
 * @MappedSuperclass
 */
abstract class AbstractUser
{
    /** 
     * @Id @Column(type="integer") @GeneratedValue 
     * @param integer
     */
    protected $id;

    /**
     * @Column(type="string", length=100)
     * @var string
     */
    protected $name;
    
    /**
     * @Column(type="string", length=100, unique=true)
     * @var string
     */
    protected $email;
    
    /**
     * @Column(type="string", length=40)
     * @var string
     */
    protected $passwordHash;
    
    /**
     * @param string $name
     * @param string $email
     * @param string $password
     */
    protected function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = AbstractAuth::getPasswordHash($password);
    }
    
    /**
     * Retruns a brand new user, not saved in DB yet
     * @param string $name
     * @param string $email
     * @param string $password
     * @return AbstractUser
     */
    public static function create($name, $email, $password)
    {
        return new static($name, $email, $password);
    }
    
    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }
    
    /**
     * @param string $name
     * @param string $email
     * @return \horses\plugin\auth\AbstractUser
     */
    public function updateData($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
        
        return $this;
    }
    
    /**
     * @param string $password
     * @return \horses\plugin\auth\AbstractUser
     */
    public function updatePassword($password)
    {
        $this->passwordHash = AbstractAuth::getPasswordHash($password);
        
        return $this;
    }
    
    /**
     * @param string $password
     * @return boolean
     */
    public function isPasswordValid($password)
    {
        return $this->passwordHash == AbstractAuth::getPasswordHash($password);
    }
    
    /**
     * Resets the password to a new one, set the hash in the object and return
     * the password (to include in an email presumably)
     * @return string
     */
    public function resetPassword()
    {
        $pass = '';
        for ($i = 0; $i < mt_rand(6, 9); $i++) {
            $pass .= chr(mt_rand(33, 122));
        }
        $this->passwordHash = AbstractAuth::getPasswordHash($pass);
        
        return $pass;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}