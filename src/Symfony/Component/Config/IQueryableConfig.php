<?php

namespace Symfony\Component\Config;

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
     * @return Symfony\Component\Config\ConfigAbstract $this
     */
    public function set($name, $value);
}
