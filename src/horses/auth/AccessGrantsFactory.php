<?php

namespace horses\auth;

interface AccessGrantsFactory
{
    /**
     * @param User $user
     * @return AccessGrants
     */
    public function getGrantsForUser(User $user);
}