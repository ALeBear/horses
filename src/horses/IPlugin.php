<?php

namespace horses;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface for horses's plugins
 */
interface IPlugin
{
    /**
     * Bootstrap our plugin by registering services in the dependency injection
     * container
     * @param Symfony\Component\HttpFoundation\Request 
     * @param Symfony\Component\DependencyInjection\Container $dependencyInjectionContainer
     */
    public function bootstrap(Request $request, Container $dependencyInjectionContainer);
   
    /**
     * The dispatch process has begun
     * @param Symfony\Component\HttpFoundation\Request 
     * @param Symfony\Component\DependencyInjection\Container $dependencyInjectionContainer
     */
    public function dispatch(Request $request, Container $dependencyInjectionContainer);
}
