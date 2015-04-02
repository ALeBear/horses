<?php

namespace horses\responder\view\html;

abstract class Partial extends HtmlFileTemplate
{
    /** @var Layout */
    protected $layout;

    /**
     * @param Layout $layout
     */
    public function __construct(Layout $layout)
    {
        $this->layout = $layout;
    }
}
