<?php

namespace horses\action;

use horses\responder\Responder;
use horses\Request;
use horses\Router;

interface Action
{
    /**
     * @param Request $request
     * @param Router $router
     * @return Responder
     */
    public function execute(Request $request, Router $router);
}
