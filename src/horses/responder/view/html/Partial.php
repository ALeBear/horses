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

    /** @inheritdoc */
    public function getRendering()
    {
        $this->preRender();
        return parent::getRendering();
    }

    /**
     * Can be used for pre-rendering calculations
     * @return $this
     */
    protected function preRender()
    {
        return $this;
    }
}
