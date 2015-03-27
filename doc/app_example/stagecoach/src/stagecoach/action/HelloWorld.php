<?php

namespace stagecoach\action;

use horses\action\Action;
use Symfony\Component\HttpFoundation\Request;

class HelloWorld implements Action
{
    /** @inheritdoc */
    public function execute(Request $request)
    {
        return new HelloWorldResponder();
    }

}
