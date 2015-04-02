<?php

namespace horses\responder\view;

interface Composite extends View
{
    /**
     * @param string $name
     * @param View $part
     * @return $this
     */
    public function addPart($name, View $part);
}
