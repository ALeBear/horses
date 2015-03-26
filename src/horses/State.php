<?php

namespace horses;

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
     * @return string
     */
    public function getUserId()
    {
        return $this->session->get(self::USER_KEY);
    }

    /**
     * @param string $userId
     * @return $this
     */
    public function saveUserId($userId)
    {
        $this->session->set(self::USER_KEY, $userId);
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