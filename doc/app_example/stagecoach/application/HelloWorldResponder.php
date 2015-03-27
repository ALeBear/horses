<?php

namespace stagecoach\application;

use horses\responder\Responder;

class HelloWorldResponder implements Responder
{
    /** @inheritdoc */
    public function output()
    {
        echo 'Hello, World!';
    }
}