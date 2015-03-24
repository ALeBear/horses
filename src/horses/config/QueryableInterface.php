<?php

namespace horses\config;

interface QueryableInterface
{
    /**
     * Get a value, values are namespaced by dots
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null);
    
    /**
     * Temporarily (for the current PHP call) set a config value
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function set($name, $value);
}
