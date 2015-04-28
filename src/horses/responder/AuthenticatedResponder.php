<?php

namespace horses\responder;

use horses\auth\User;

interface AuthenticatedResponder
{
    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user);
}
