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
        $responder = new IndexResponder();
        $responder->setUsername($this->user->__toString());

        return $responder;
    }
}
