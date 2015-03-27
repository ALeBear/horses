<?php

namespace stagecoach\action;

use horses\responder\Responder;

class HelloWorldResponder implements Responder
{
    /** @inheritdoc */
    public function output()
    {
        echo 'Hello, World!';
    }
}
