<?php

namespace horses\auth;

interface AccessPolicy
{
    /**
     * @param AccessGrants $accessGrants
     * @throws AccessControlException If authorization failed
     */
    public function authorize(AccessGrants $accessGrants);

    /**
     * @return string
     */
    public function __toString();
}