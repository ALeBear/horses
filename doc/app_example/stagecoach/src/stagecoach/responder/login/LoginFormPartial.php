<?php

namespace stagecoach\responder\login;

use horses\responder\view\html\Partial;

class LoginFormPartial extends Partial
{
    /**
     * @return string
     */
    protected function getTemplatePath()
    {
        return __DIR__ . '/login.html.php';
    }
}
