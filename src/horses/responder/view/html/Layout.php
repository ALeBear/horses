<?php

namespace horses\responder\view\html;

abstract class Layout extends HtmlFileTemplate
{
    const VAR_JAVASCRIPTS = 'head_javascripts';
    const VAR_CSSES = 'head_csses';
    const VAR_METAS = 'head_metas';
    const VAR_TITLE = 'head_title';

    /**
     * @param string $path
     * @return $this
     */
    public function addJavascript($path)
    {
        if (!isset($this->variables[self::VAR_JAVASCRIPTS])) {
            $this->variables[self::VAR_JAVASCRIPTS] = [];
        }
        $this->variables[self::VAR_JAVASCRIPTS][] = $path;
        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function addCss($path)
    {
        if (!isset($this->variables[self::VAR_CSSES])) {
            $this->variables[self::VAR_CSSES] = [];
        }
        $this->variables[self::VAR_CSSES][] = $path;
        return $this;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setTitle($text)
    {
        $this->variables[self::VAR_TITLE] = $text;
    }

    /**
     * @param string $name
     * @param string $content
     * @return mixed
     */
    public function setMeta($name, $content)
    {
        if (!isset($this->variables[self::VAR_METAS])) {
            $this->variables[self::VAR_METAS] = [];
        }
        $this->variables[self::VAR_METAS][] = ['name' => $name, 'content' => $content];
        return $this;
    }
}
