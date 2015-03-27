<?php

namespace horses;

use horses\auth\UserId;
use Symfony\Component\HttpFoundation\Session\Session;

class State
{
    const USER_KEY = 'horses_userId';

    /** @var  Session */
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @return UserId
     */
    public function getUserId()
    {
        return new UserId($this->session->get(self::USER_KEY));
    }

    /**
     * @param UserId $userId
     * @return $this
     */
    public function saveUserId(UserId $userId)
    {
        $this->session->set(self::USER_KEY, $userId->getId());
        return $this;
    }

    /**
     * @return $this
     */
    public function deleteUserId()
    {
        $this->session->set(self::USER_KEY, null);
        return $this;
    }
}