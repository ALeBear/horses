<?php

namespace stagecoach\responder;

use horses\responder\Responder;
use horses\Router;

class StaticTextResponder implements Responder
{
    /** @var  string */
    protected $text;

    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /** @inheritdoc */
    public function output(Router $router)
    {
        $layout = new BasicLayout();
        $layout->addVariable('menu', '');
        $layout->addVariable('content', $this->text);
        echo $layout->getRendering();
    }
}
