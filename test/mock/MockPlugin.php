<?php

namespace horses\test\mock;

use horses\Router;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

class MockPlugin
{
    public $bootstrapped = null;
    public $dispatched = null;
    protected $name;
    
    public function __construct($name)
    {
        $this->name = $name;
    }
    
    public function bootstrap(Request $request, Container $dependencyInjectionContainer)
    {
        $this->bootstrapped = microtime(true);
        if ($this->name == 'main') {
            $dependencyInjectionContainer->set('router', new Router());
        }
        return $this;
    }
   
    public function dispatch(Request $request, Container $dependencyInjectionContainer)
    {
        $this->dispatched = microtime(true);
        return $this;
    }
}