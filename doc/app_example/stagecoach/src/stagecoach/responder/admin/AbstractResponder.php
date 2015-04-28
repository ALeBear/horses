<?php

namespace stagecoach\responder\admin;

use \stagecoach\responder\AbstractResponder as BaseResponder;
use horses\Router;

abstract class AbstractResponder extends BaseResponder
{
    /**
     * @param Router $router
     * @return AdminLayout
     */
    protected function prepareLayout(Router $router)
    {
        $layout = new AdminLayout();
        $layout->setRouter($router);
        $layout->addVariable('username', $this->user->__toString());
        $layout->addVariable('message', $this->message);

        return $layout;
    }
}
