<?php

namespace horses\responder\view\html;

use horses\responder\view\FileTemplate;

abstract class HtmlFileTemplate extends FileTemplate
{
    /**
     * @param string $string
     * @return string
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE);
    }
    /**
     * @param string $string
     * @return string
     */
    public function escapeForAttribute($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE);
    }

    /**
     * @param Partial $partial
     * @return string
     */
    public function renderPartial(Partial $partial)
    {
        $partial->addVariables($this->variables);
        return $partial->getRendering();
    }
}
