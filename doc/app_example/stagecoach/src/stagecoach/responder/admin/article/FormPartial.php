<?php

namespace stagecoach\responder\admin\article;

use horses\responder\view\html\Partial;

class FormPartial extends Partial
{
    /**
     * @return string
     */
    protected function getTemplatePath()
    {
        return __DIR__ . '/form.html.php';
    }
}
