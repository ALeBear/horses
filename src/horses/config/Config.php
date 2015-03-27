<?php

namespace horses\config;

use Symfony\Component\Config\Loader\LoaderInterface;

class Config implements Queriable
{
    const LEVEL_SEPARATOR = '.';

    /**
     * @param mixed[]
     */
    protected $values = array();
    
    
    /**
     * @param string $resource Usually the config file name, which may be found
     * at multiple locations in the loader's locator, then merged
     * @param LoaderInterface $loader
     */
    public function __construct($resource, LoaderInterface $loader)
    {
        $this->values = call_user_func_array('array_replace_recursive', $loader->load($resource));
    }

    /**
     * Get a value. Subvalues are separated by dots
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        $val =& $this->values;
        foreach (explode(self::LEVEL_SEPARATOR, $name) as $key) {
            if (!is_array($val) || !array_key_exists($key, $val)) {
                return $default;
            }
            $val =& $val[$key];
        }
        
        return $val;
    }

    /**
     * Temporarily (for the current PHP call) set a config value
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function set($name, $value)
    {
        $val =& $this->values;
        $key = $name;
        foreach (explode(self::LEVEL_SEPARATOR, $name) as $key) {
            if (!isset($val[$key])) {
                $val[$key] = [];
            } else if (!is_array($val[$key])) {
                $val[$key] = [$key => $val[$key]];
            }
            $parentVal =& $val;
            $val =& $val[$key];
        }
        $parentVal[$key] = $value;

        return $this;
    }
}
