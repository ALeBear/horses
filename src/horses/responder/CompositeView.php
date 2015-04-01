<?php

namespace horses\responder;

interface CompositeView extends View
{
    /**
     * @param string $name
     * @param View $part
     * @return $this
     */
    public function addPart($name, View $part);
}
