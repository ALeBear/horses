<?php

namespace horses\auth;

interface User
{
    /**
     * @return UserId
     */
    public function getUserId();

    /**
     * @return AccessGrants
     */
    public function getAccessGrants();

    /**
     * @return string
     */
    public function __toString();
}
