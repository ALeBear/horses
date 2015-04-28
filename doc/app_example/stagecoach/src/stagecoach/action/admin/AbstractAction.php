<?php

namespace stagecoach\action\admin;

use stagecoach\action\AbstractAction as BaseAction;
use horses\doctrine\SimpleAccessPolicy;

abstract class AbstractAction extends BaseAction
{
    /** @inheritdoc */
    public function getAccessPolicy()
    {
        return new SimpleAccessPolicy(['admin']);
    }
}
