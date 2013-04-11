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
     * @var IPlugin
     */
    protected $defaultPlugin;
    
    
    /**
     * @param array $plugins
     * @param \horses\test\IPlugin $defaultPlugin
     */
    public function __construct(array $plugins = array(), IPlugin $defaultPlugin = null)
    {
        $this->plugins = $plugins;
        $this->defaultPlugin = $defaultPlugin;
    }
    
    /**
     * Takes a full classname (ns\subns\...\class), or a simplified name for
     * core plugins ('auth', 'locale', 'main', 'doctrine')
     * @param string $definition
     * @return IPlugin
     */
    public function locate($definition)
    {
        return array_key_exists($definition, $this->plugins)
            ? $this->plugins[$definition]
            : $this->defaultPlugin;
    }
}
