<?php

namespace horses\action;

interface AuthenticatedActionInterface
{
    public function setAuthentication($user);
}
