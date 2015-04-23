<?php

namespace stagecoach\responder\admin;

use horses\responder\view\html\Partial;

class AdminMenuPartial extends Partial
{
    /**
     * @return string
     */
    protected function getTemplatePath()
    {
        return __DIR__ . '/admin_menu.html.php';
    }
}
