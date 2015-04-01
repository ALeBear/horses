<?php

namespace stagecoach;


use horses\auth\Authorization;

class DummyAuthorization implements Authorization
{
    /**
     * @return string
     */
    public function __toString()
    {
        return 'blah';
    }
}
