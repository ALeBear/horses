<?php

namespace horses\action;
use Symfony\Component\HttpFoundation\Session\Session;

interface StatefulActionInterface
{
    /**
     * @param Session $session
     * @return $this
     */
    public function setSession(Session $session);
}
