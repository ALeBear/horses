<?php

namespace horses;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Session\Session;
use Exception;

class Kernel
{
    /**
     * @var string The name of the main plugin
     */
    const MAIN_PLUGIN = 'main';
    
    /**
     * @var string List of mandatory modules separated by commas
     */
    const MANDATORY_PLUGINS = 'main';
    
    /**
     * @return \horses\Kernel
     */
    public static function factory()
    {
        return new static();
    }
    
    /**
     * @param string[] $plugins Class names (no namespacing = horses's natives)
     * @param boolean $bootstrapOnly Only a bootstrap if set to true
     * @return Symfony\Component\DependencyInjection\Container
     * @throws InvalidArgumentException When a plugin file is not found
     */
    public function run(array $plugins, $bootstrapOnly = false)
    {
        try {
            $request = Request::createFromGlobals();
            $session = new Session();
            $session->start();
            $request->setSession($session);

            //Set some attributes
            $request->attributes->set('BOOTSTRAP_ONLY', $bootstrapOnly);
            $request->attributes->set('ENV', isset($_SERVER['ENV']) ? $_SERVER['ENV'] : 'prod');
            $request->attributes->set('DIR_BASE', realpath(__DIR__ . '/../..'));
            $request->attributes->set('DIR_APPLICATION', $request->attributes->get('DIR_BASE') . '/application');
            $request->attributes->set('DIR_LIB', $request->attributes->get('DIR_BASE') . '/lib');
            $request->attributes->set('DIR_CONTROLLERS', $request->attributes->get('DIR_APPLICATION') . '/controller');

            //Instantiate plugins
            $plugins = $this->instantiatePlugins($plugins);
            $mainPlugin = $plugins[self::MAIN_PLUGIN];
            unset($plugins[self::MAIN_PLUGIN]);

            $DIContainer = new ContainerBuilder();

            //Bootstrap
            $mainPlugin->bootstrap($request, $DIContainer);
            foreach ($plugins as $plugin) {
                $plugin->bootstrap($request, $DIContainer);
            }

            //Route
            $DIContainer->get('router')->route($request);

            //Dispatch
            if (!$bootstrapOnly) {
                foreach ($plugins as $plugin) {
                    $plugin->dispatch($request, $DIContainer);
                }
                $mainPlugin->dispatch($request, $DIContainer);
            }

            return $DIContainer;
        } catch (Kernel404Exception $e) {
            $errorFile = sprintf('%s/404.php', str_replace('//', '', $_SERVER['DOCUMENT_ROOT']));
            if (file_exists($errorFile)) {
                require $errorFile;
            }
        } catch (Exception $e) {
            $errorFile = sprintf('%s/500.php', str_replace('//', '', $_SERVER['DOCUMENT_ROOT']));
            if (file_exists($errorFile)) {
                require $errorFile;
            }
        }
    }
    
    /**
     * @param string[] $pluginsList
     * @return \horses\IPlugin[] Array of plugins indexed by class name
     * @throws InvalidArgumentException When a plugin is not found in files
     */
    protected function instantiatePlugins($pluginsList)
    {
        
        $plugins = array();
        foreach (array_unique(array_merge(explode(',', self::MANDATORY_PLUGINS), $pluginsList)) as $plugin) {
            $class = strpos($plugin, '\\') === false
                ? sprintf('horses\\plugin\\%s\\Plugin', $plugin)
                : $plugin;
            $plugins[$plugin] = new $class();
        }
        
        return $plugins;
    }
}
