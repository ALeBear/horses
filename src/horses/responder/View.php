<?php

namespace horses\responder;

interface View
{
    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function addVariable($name, $value);

    /**
     * @param mixed[] $variables Associative array of name => value pairs
     * @return $this
     */
    public function addVariables(array $variables);

    /**
     * @return string
     */
    public function getRendering();
}
