<?php

namespace horses\plugin\main;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use horses\IPlugin;
use horses\Symfony\YamlFileLoader;
use horses\Symfony\ConfigCollection;
use horses\KernelPanicException;
use horses\Kernel404Exception;
use horses\Router;
use horses\View;
use horses\AbstractController;

class Plugin implements IPlugin
{
    public function bootstrap(Request $request, Container $dependencyInjectionContainer)
    {
        $configDir = $request->attributes->get('DIR_APPLICATION') . '/config';
        if (!is_dir($configDir)) {
            throw new KernelPanicException(sprintf('Config dir does not exists or not readable: %s', $configDir));
        }
        
        $loader = new YamlFileLoader(
            new FileLocator(array($configDir, $configDir . '/' . $request->attributes->get('ENV'))));

        $dependencyInjectionContainer->set('config_loader', $loader);
        $dependencyInjectionContainer->set('config', ConfigCollection::factory()
            ->add('kernel', new KernelConfig('kernel.yml', $loader))
            ->add('view', new ViewConfig('view.yml', $loader)));
        $request->attributes->set('DIR_HTDOCS', $request->attributes->get('DIR_BASE') . '/' . $dependencyInjectionContainer->get('config')->get('kernel.htdocsDir'));
        $router = new Router();
        $dependencyInjectionContainer->get('config')->get('kernel.urlPrefix') && $router->addPrefix($dependencyInjectionContainer->get('config')->get('kernel.urlPrefix'));
        $dependencyInjectionContainer->set('router', $router);
    }
    
    public function dispatch(Request $request, Container $dependencyInjectionContainer)
    {
        //Load controller file
        $controllerFile = sprintf('%s/%s/%s.php',
            $request->attributes->get('DIR_CONTROLLERS'),
            $request->attributes->get('MODULE'),
            ucfirst($request->attributes->get('ACTION')));
        if (!is_file($controllerFile)) {
            throw new Kernel404Exception(sprintf('Controller not found: %s', $controllerFile));
        }
        require $controllerFile;
        
        //Verify view and layout files
        $viewFile = sprintf('%s/%s/%s-view.php',
            $request->attributes->get('DIR_CONTROLLERS'),
            $request->attributes->get('MODULE'),
            ucfirst($request->attributes->get('ACTION')));
        if (!is_file($viewFile)) {
            throw new KernelPanicException(sprintf('View not found: %s', $viewFile));
        }
        $conf = $dependencyInjectionContainer->get('config');
        $layoutFile = sprintf('%s/%s',
            $request->attributes->get('DIR_APPLICATION'),
            $conf->get(sprintf('view.layout.%s', $request->attributes->get('ROUTE'))) ? : $conf->get('view.layout.default'));
        if (!is_file($layoutFile)) {
            throw new KernelPanicException(sprintf('Layout not found: %s', $layoutFile));
        }
        
        $controllerClass = sprintf('\\horses\\controller\\%s\\%s', $request->attributes->get('MODULE'), ucfirst($request->attributes->get('ACTION')));
        if (!class_exists($controllerClass)) {
            throw new KernelPanicException(sprintf('Controller class not declared: %s', $controllerClass));
        }
        
        $controller = new $controllerClass($dependencyInjectionContainer, new View($viewFile, $layoutFile));
        if (!method_exists($controllerClass, 'execute')) {
            throw new KernelPanicException(sprintf('Controller class must declare an "execute" method: %s', $controllerClass));
        }
        if (!($controller instanceof AbstractController)) {
            throw new KernelPanicException(sprintf('Controller class must be a subclass of AbstractController: %s', $controllerClass));
        }
        
        //Launch the real dispatch process
        $controller->dispatch($request, new Response('', 200, array('content-type' => 'text/html')));
    }
}
