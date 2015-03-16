<?php

namespace Symfony\Component\Config;

use InvalidArgumentException;

/**
 * A collection of config files
 */
class Collection implements IQueryableConfig
{
    /**
     * @var ConfigAbstract[]
     */
    protected $configs = array();
    
    /**
     * Factory method for fluent interfaces
     * @return Collection
     */
    public static function factory()
    {
        return new static();
    }
    
    /**
     * @param string $name
     * @param ConfigAbstract $config
     * @return Collection $this
     */
    public function add($name, ConfigAbstract $config)
    {
        $this->configs[$name] = $config;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     * @throws InvalidArgumentException If no section (no dot in $name)
     * @throws InvalidArgumentException If section not loaded
     */
    public function get($name, $default = null)
    {
        if (strpos($name, '.') === false) {
            throw new InvalidArgumentException(sprintf("You need at least the section name before the param name, separated by a dot, not: %s", $name));
        }
        
        list($section, $param) = explode('.', $name, 2);
        if (!array_key_exists($section, $this->configs)) {
            throw new InvalidArgumentException(sprintf("Config not loaded: %s", $section));
        }
        
        return $this->configs[$section]->get($param, $default);
    }
    
    /**
     * @param string $name
     * @return boolean
     */
    public function hasSection($name)
    {
        return array_key_exists($name, $this->configs);
    }
    
    public function set($name, $value) {}
    
    public function getConfigTreeBuilder() {}
}