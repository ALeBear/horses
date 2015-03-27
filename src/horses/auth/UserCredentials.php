<?php

namespace horses\auth;

interface UserCredentials
{
    /**
     * @return string
     */
    public function getLogin();

    /**
     * @return string
     */
    public function getPassword();
}