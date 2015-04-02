<?php

namespace stagecoach\responder;

use horses\responder\view\html\Layout;

class BasicLayout extends Layout
{
    /**
     * @return string
     */
    protected function getTemplatePath()
    {
        return __DIR__ . '/basic.html.php';
    }
}
