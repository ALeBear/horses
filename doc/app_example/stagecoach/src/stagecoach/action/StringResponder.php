<?php

namespace stagecoach\action;

use horses\responder\Responder;

class StringResponder implements Responder
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
    public function output()
    {
        echo $this->stringToDisplay;
    }
}
