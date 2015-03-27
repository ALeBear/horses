<?php

namespace stagecoach\application;

use horses\action\Action;
use Symfony\Component\HttpFoundation\Request;

class HelloWorldAction implements Action
{
    /** @inheritdoc */
    public function execute(Request $request)
    {
        return new HelloWorldResponder();
    }

}