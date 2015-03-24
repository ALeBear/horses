<?php

namespace horses\config;

use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * A collection of config files
 */
class Factory
{
    /** @var LoaderInterface */
    protected $loader;
    /** @var  string */
    protected $configClass;


    public function __construct(LoaderInterface $loader, $configClass)
    {
        $this->loader = $loader;
        if (!($configClass instanceof Config)) {
            throw new InvalidConfigClassException(sprintf('Not a horses\config\Config class: %s', $configClass));
        }
    }

    /**
     * @param string $name
     * @return Collection $this
     */
    public function getConfig($name)
    {
        return new $this->configClass($name, $this->loader);
    }
}