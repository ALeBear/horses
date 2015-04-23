<?php

namespace stagecoach\responder\admin;

use horses\responder\view\html\Layout;

class AdminLayout extends Layout
{
    /**
     * @return string
     */
    protected function getTemplatePath()
    {
        return sprintf('%s/admin_layout.html.php', __DIR__);
    }
}
