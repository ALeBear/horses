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
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}