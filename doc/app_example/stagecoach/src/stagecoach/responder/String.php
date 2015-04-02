<?php

namespace stagecoach\responder;

use horses\responder\Responder;
use horses\Router;

class String implements Responder
{
    /** @var string */
    protected $stringToDisplay;

    /**
     * @param string $stringToDisplay
     */
    public function __construct($stringToDisplay)
    {
        $this->stringToDisplay = $stringToDisplay;
    }

    /** @inheritdoc */
    public function output(Router $router)
    {
        echo $this->stringToDisplay;
    }
}
