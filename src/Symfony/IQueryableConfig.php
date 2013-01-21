<?php

namespace horses\Symfony;

interface IQueryableConfig
{
    /**
     * Get a value. Subvalues are separated by dots
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null);
    
    /**
     * Temporarily (for the current PHP call) set a config value
     * @param string $name
     * @param mixed $value
     * @return horses\Symfony\ConfigAbstract $this
     */
    public function set($name, $value);
}
