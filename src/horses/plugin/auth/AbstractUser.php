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
     * @param string $email
     * @param string $passwordHash
     */
    protected function __construct($name, $email, $passwordHash)
    {
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }
    
    /**
     * Retruns a brand new user, not saved in DB
     * @param string $email
     * @param string $passwordHash
     * @return User
     */
    public static function create($name, $email, $passwordHash)
    {
        return new static($name, $email, $passwordHash);
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
        $this->passwordHash = Auth::getPasswordHash($pass);
        
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