<?php

namespace stagecoach\action\admin;

use horses\Request;
use horses\Router;
use stagecoach\responder\admin\IndexResponder;

class Index extends AbstractAction
{
    /** @inheritdoc */
    public function execute(Request $request, Router $router)
    {
        $responder = $this->prepareResponder(new IndexResponder());

        return $responder;
    }
}
