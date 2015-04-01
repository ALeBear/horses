<?php

namespace horses\responder;

interface HtmlPageView
{
    /**
     * @param string $path
     * @return $this
     */
    public function addJavascript($path);

    /**
     * @param string $path
     * @return $this
     */
    public function addCss($path);

    /**
     * @param string $text
     * @return $this
     */
    public function setTitle($text);

    /**
     * @param string $name
     * @param string $content
     * @return mixed
     */
    public function setMeta($name, $content);
}
