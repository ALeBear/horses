<?php

namespace Symfony\Component\Config;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Definition\Processor;

/**
 * The file subclassed by clients to get config vars
 */
abstract class ConfigAbstract implements ConfigurationInterface, IQueryableConfig
{
    /**
     * @param mixed[]
     */
    protected $values = array();
    
    
    /**
     * @param string $resource Usually the config file name, which may be found
     * at multiple locations in the loader's locator, then merged
     * @param \Symfony\Component\Config\Loader\LoaderInterface $loader
     */
    public function __construct($resource, LoaderInterface $loader)
    {
        $processor = new Processor();
        $this->values = $processor->processConfiguration($this, $loader->load($resource));
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
        foreach (explode('.', $name) as $key) {
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
     * @return \Symfony\Component\Config\ConfigAbstract $this
     */
    public function set($name, $value)
    {
        //TODO
    }
}
