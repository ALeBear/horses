<?php

namespace horses\responder;

use horses\Router;

interface Responder
{
    /**
     * @param Router $router
     * @return void
     */
    public function output(Router $router);
}
