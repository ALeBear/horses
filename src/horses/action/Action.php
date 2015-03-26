<?php

namespace horses\action;

use horses\responder\ResponderInterface;

interface Action
{
    public function getAuthorizationsNeeded();

    /**
     * @param Request $request
     * @return ResponderInterface
     */
    public function execute(Request $request);
}
