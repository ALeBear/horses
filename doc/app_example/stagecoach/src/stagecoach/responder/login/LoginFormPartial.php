<?php

namespace stagecoach\responder\login;

use stagecoach\responder\AbstractPartial;

class LoginFormPartial extends AbstractPartial
{
    /**
     * @return string
     */
    protected function getTemplatePath()
    {
        return __DIR__ . '/login.html.php';
    }
}
