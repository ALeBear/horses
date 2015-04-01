<?php

namespace stagecoach\action;

use horses\action\Action;
use horses\Request;

class HelloWorld implements Action
{
    /** @inheritdoc */
    public function execute(Request $request)
    {
        return new StringResponder('Hello, World!');
    }

}
