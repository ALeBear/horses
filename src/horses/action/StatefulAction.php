<?php

namespace horses\action;

use horses\State;

interface StatefulAction
{
    /**
     * @param State $state
     * @return $this
     */
    public function setState(State $state);

    /**
     * @return State
     */
    public function getState();

}
