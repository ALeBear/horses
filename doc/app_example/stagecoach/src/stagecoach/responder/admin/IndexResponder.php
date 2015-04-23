<?php

namespace stagecoach\responder\admin;

use horses\Router;

class IndexResponder extends AbstractResponder
{
    /** @inheritdoc */
    public function output(Router $router)
    {
        $layout = $this->prepareLayout($router);
        $layout->addVariable('content', "Bienvenue dans la section d'administration");
        echo $layout->getRendering();
    }
}
