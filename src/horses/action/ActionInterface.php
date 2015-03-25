<?php

namespace horses\action;

use horses\responder\ResponderInterface;
use Symfony\Component\HttpFoundation\Request;

interface ActionInterface
{
    public function getAuthorizationCredentials();

    /**
     * @param Request $request
     * @return ResponderInterface
     */
    public function execute(Request $request);
}
