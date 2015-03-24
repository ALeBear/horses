<?php

namespace horses;

/**
 * Basic plugin scheduler, will set the order of plugins for the bootstrap and
 * dispatch processes. 
 */
class PluginScheduler
{
    /**
     * Return a properly ordered version of the given array for the dispatch
     * process
     * @param string[] $plugins
     * @return string[]
     */
    public function orderForDispatch(array $plugins)
    {
        $core = $nonCore = array();
        foreach ($plugins as $plugin) {
            if ($plugin == FrontController::MAIN_PLUGIN) {
                continue;
            }
            if (strpos($plugin, '\\') === false) {
                $core[] = $plugin;
            } else {
                $nonCore[] = $plugin;
            }
        }
        
        return array_merge($this->orderCorePluginsForDispatch($core), $this->orderNonCorePluginsForDispatch($nonCore), array(FrontController::MAIN_PLUGIN));
    }

    /**
     * Return a properly ordered version of the given array for the bootstrap
     * process
     * @param string[] $plugins
     * @return string[]
     */
    public function orderForBootstrap(array $plugins)
    {
        $core = $nonCore = array();
        foreach ($plugins as $plugin) {
            if ($plugin == FrontController::MAIN_PLUGIN) {
                continue;
            }
            if (strpos($plugin, '\\') === false) {
                $core[] = $plugin;
            } else {
                $nonCore[] = $plugin;
            }
        }
        
        return array_merge(array(FrontController::MAIN_PLUGIN), $this->orderCorePluginsForBootstrap($core), $this->orderNonCorePluginsForBootstrap($nonCore));
    }
    
    /**
     * Order the core plugins for the dispatch process
     * @param string[] $plugins
     * @return string[]
     */
    protected function orderCorePluginsForDispatch(array $plugins)
    {
        return $plugins;
    }
    
    /**
     * Order the non core plugins for the dispatch process
     * @param string[] $plugins
     * @return string[]
     */
    protected function orderNonCorePluginsForDispatch(array $plugins)
    {
        return $plugins;
    }
    
    /**
     * Order the core plugins for the bootstrap process
     * @param string[] $plugins
     * @return string[]
     */
    protected function orderCorePluginsForBootstrap(array $plugins)
    {
        if (!in_array('doctrine', $plugins)) {
            return $plugins;
        }
        
        unset($plugins[array_search('doctrine', $plugins)]);
        array_push($plugins, 'doctrine');
        return $plugins;
    }
    
    /**
     * Order the non core plugins for the bootstrap process
     * @param string[] $plugins
     * @return string[]
     */
    protected function orderNonCorePluginsForBootstrap(array $plugins)
    {
        return $plugins;
    }
}