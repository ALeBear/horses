<?php

namespace horses\auth;

interface Credentials
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