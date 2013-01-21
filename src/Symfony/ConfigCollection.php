<?php

namespace horses\Symfony;

use InvalidArgumentException;

/**
 * A collection of config files
 */
class ConfigCollection implements IQueryableConfig
{
    /**
     * @var Config[]
     */
    protected $configs = array();
    
    /**
     * Factory method for fluent interfaces
     * @return ConfigCollection
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
    
    public function set($name, $value) {}
    
    public function getConfigTreeBuilder() {}
}