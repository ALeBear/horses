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
     * @var string The name of the default environement
     */
    const DEFAULT_ENV = 'prod';

    /**
     * @var string The name of the main plugin
     */
    const MAIN_PLUGIN = 'main';
    
    /**
     * @var string List of mandatory modules separated by commas
     */
    const MANDATORY_PLUGINS = 'main';

    
    /**
     * @var horses\PluginLocator 
     */
    protected $pluginLocator;
    
    /**
     * @var Symfony\Component\HttpFoundation\Request 
     */
    protected $request;
    
    /**
     * @var Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;
    
    /**
     * @var horses\PluginScheduler
     */
    protected $scheduler;
    
    
    /**
     * @return \horses\Kernel
     */
    public static function factory()
    {
        return new static();
    }
    
    /**
     * @param string $baseDir The base directory, usually the parent directory of it all
     * @param string[] $plugins Class names (no namespacing = horses' core plugin)
     * @param boolean $bootstrapOnly Only a bootstrap if set to true, no dispatch
     * @return Symfony\Component\DependencyInjection\Container
     * @throws InvalidArgumentException When a plugin file is not found
     */
    public function run($baseDir, array $plugins, $bootstrapOnly = false)
    {
        try {
            $request = $this->getRequest();
            $request->setSession($this->getSession());

            //Set some attributes
            $request->attributes->set('BOOTSTRAP_ONLY', $bootstrapOnly);
            $request->attributes->set('ENV', $request->server->get('ENV', static::DEFAULT_ENV));
            $request->attributes->set('DIR_BASE', $baseDir);
            $request->attributes->set('DIR_APPLICATION', $request->attributes->get('DIR_BASE') . '/application');
            $request->attributes->set('DIR_LIB', $request->attributes->get('DIR_BASE') . '/lib');
            $request->attributes->set('DIR_CONTROLLERS', $request->attributes->get('DIR_APPLICATION') . '/controller');

            $DIContainer = new ContainerBuilder();
            $scheduler = $this->getScheduler();

            //Instantiate plugins
            $pluginObjs = array();
            $plugins = array_unique(array_merge(explode(',', self::MANDATORY_PLUGINS), $plugins));
            foreach ($plugins as $plugin) {
                $pluginObjs[$plugin] = $this->getPluginLocator()->locate($plugin);
            }

            //Bootstrap
            foreach ($scheduler->orderForBootstrap($plugins) as $pluginName) {
                $pluginObjs[$pluginName]->bootstrap($request, $DIContainer);
            }

            //Route
            $DIContainer->get('router')->route($request);

            //Dispatch
            if (!$bootstrapOnly) {
                foreach ($scheduler->orderForDispatch($plugins) as $pluginName) {
                    $pluginObjs[$pluginName]->dispatch($request, $DIContainer);
                }
            }

            return $DIContainer;
        } catch (Kernel404Exception $e) {
            $errorFile = sprintf('%s/404.php', str_replace('//', '', $_SERVER['DOCUMENT_ROOT']));
            header('HTTP/1.0 404 Not Found');
            if (file_exists($errorFile)) {
                require $errorFile;
            } else {
                throw $e;
            }
        } catch (Exception $e) {
            $errorFile = sprintf('%s/500.php', str_replace('//', '', $_SERVER['DOCUMENT_ROOT']));
            if (file_exists($errorFile)) {
                header('HTTP/1.0 500 Internal Server Error');
                require $errorFile;
            } else {
                throw $e;
            }
        }
    }
    
    /**
     * Used mainly for the unit tests, otherwise a PluginLocator will be
     * instantiated
     * @param \horses\PluginLocator $locator
     * @return \horses\Kernel
     */
    public function injectPluginLocator(PluginLocator $locator)
    {
        $this->pluginLocator = $locator;
        return $this;
    }
    
    /**
     * Used mainly for the unit tests, otherwise a Request will be
     * instantiated from the superglobal arrays
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return \horses\Kernel
     */
    public function injectRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }
    
    /**
     * Used mainly for the unit tests, otherwise a PluginScheduler will be
     * instantiated
     * @param horses\PluginScheduler $scheduler
     * @return \horses\Kernel
     */
    public function injectScheduler(PluginScheduler $scheduler)
    {
        $this->scheduler = $scheduler;
        return $this;
    }
    
    /**
     * Used mainly for the unit tests, otherwise a Session will be instantiated
     * and started.
     * @param Symfony\Component\HttpFoundation\Session\Session $request
     * @return \horses\Kernel
     */
    public function injectSession(Session $session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * @return horses\PluginLocator
     */
    protected function getPluginLocator()
    {
        return $this->pluginLocator ?: new PluginLocator();
    }
    
    /**
     * @return Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        return $this->request ?: Request::createFromGlobals();
    }
    
    /**
     * @return Symfony\Component\HttpFoundation\Session\Session
     */
    protected function getSession()
    {
        if ($this->session) {
            return $this->session;
        } else {
            $session = new Session();
            $session->start();
            return $session;
        }
    }
    
    /**
     * @return horses\PluginScheduler
     */
    protected function getScheduler()
    {
        return $this->scheduler ?: new PluginScheduler();
    }
}
