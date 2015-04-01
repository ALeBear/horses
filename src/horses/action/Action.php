<?php

namespace horses\action;

use horses\responder\Responder;
use horses\Request;

interface Action
{
    /**
     * @param Request $request
     * @return Responder
     */
    public function execute(Request $request);
}
