<?php

namespace stagecoach\action;

use horses\action\Action;
use horses\Request;
use horses\Router;
use stagecoach\responder\StaticTextResponder;

class HelloWorld implements Action
{
    /** @inheritdoc */
    public function execute(Request $request, Router $router)
    {
        return new StaticTextResponder('Hello, World!');
    }

}
