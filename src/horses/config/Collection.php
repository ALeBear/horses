<?php

namespace horses\config;

/**
 * A collection of config files
 */
class Collection implements QueryableInterface
{
    /** @var Factory */
    protected $factory;
    /** @var Config[] */
    protected $configs = array();


    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param string $name
     * @return Collection $this
     */
    public function load($name)
    {
        $this->configs[$name] = $this->factory->getConfig($name);
        return $this;
    }

    /** @inheritdoc */
    public function get($name, $default = null)
    {
        list($configName, $param) = $this->getConfigNameAndKey($name);
        return $this->configs[$configName]->get($param, $default);
    }
    
    /**
     * @param string $name
     * @return boolean
     */
    public function hasSection($name)
    {
        return array_key_exists($name, $this->configs);
    }

    /** @inheritdoc */
    public function set($name, $value)
    {
        list($configName, $param) = $this->getConfigNameAndKey($name);
        $this->configs[$configName]->set($param, $value);
        return $this;
    }

    /**
     * @param $name
     * @return string[] [$configName, $keyInConfig]
     * @throws InvalidCollectionKeyException If no config name in $name separated by the level separator
     * @throws UnknownConfigException If config not loaded
     */
    protected function getConfigNameAndKey($name)
    {
        if (strpos($name, Config::LEVEL_SEPARATOR) === false) {
            throw new InvalidCollectionKeyException(sprintf("You need at least the config name before the param name, separated by a '%s', not: %s", Config::LEVEL_SEPARATOR, $name));
        }

        list($configName, $param) = explode(Config::LEVEL_SEPARATOR, $name, 2);

        if (!$this->hasSection($configName)) {
            throw new UnknownConfigException(sprintf("Config not loaded: %s", $configName));
        }

        return [$configName, $param];
    }
}