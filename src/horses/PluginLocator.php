<?php

namespace horses;

/**
 * Locates plugins and instantiate them
 */
class PluginLocator
{
    /**
     * Takes a full classname (ns\subns\...\class), or a simplified name for
     * core plugins ('auth', 'locale', 'main', 'doctrine')
     * @param string $definition
     * @return IPlugin
     */
    public function locate($definition)
    {
        $class = strpos($definition, '\\') === false
            ? sprintf('horses\\plugin\\%s\\Plugin', $definition)
            : $definition;
        return new $class();
    }
}
