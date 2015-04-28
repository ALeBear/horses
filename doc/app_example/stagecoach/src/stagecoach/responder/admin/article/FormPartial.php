<?php

namespace stagecoach\responder\admin\article;

use stagecoach\responder\AbstractPartial;

class FormPartial extends AbstractPartial
{
    /**
     * @return string
     */
    protected function getTemplatePath()
    {
        return __DIR__ . '/form.html.php';
    }
}
