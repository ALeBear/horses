<?php

namespace horses\action;

use horses\responder\Responder;
use Symfony\Component\HttpFoundation\Request;

interface Action
{
    /**
     * @param Request $request
     * @return Responder
     */
    public function execute(Request $request);
}
