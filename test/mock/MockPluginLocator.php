<?php

namespace horses\test\mock;

use horses\PluginLocator;

class MockPluginLocator extends PluginLocator
{
    /**
     * @var array
     */
    protected $plugins;
    
    
    /**
     * @param array $plugins
     */
    public function __construct(array $plugins = array())
    {
        $this->plugins = $plugins;
    }
    
    /**
     * Takes a full classname (ns\subns\...\class), or a simplified name for
     * core plugins ('auth', 'locale', 'main', 'doctrine')
     * @param string $definition
     * @return IPlugin
     */
    public function locate($definition)
    {
        return $this->plugins[$definition];
    }
}
