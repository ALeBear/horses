<?php

namespace horses\auth;


interface User
{
    /**
     * @return string
     */
    public function getId();
}