<?php

namespace horses\controller\defaulter;

use horses\AbstractController;

/**
 * Homepage
 */
class Index extends AbstractController
{
    public function prepareView()
    {
        $this->view->day = date('l');
    }
}
