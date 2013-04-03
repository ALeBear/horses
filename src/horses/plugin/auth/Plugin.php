<?php

namespace horses\plugin\auth;

use horses\IPlugin;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

class Plugin implements IPlugin
{
    public function bootstrap(Request $request, Container $dependencyInjectionContainer)
    {
        /* @var $config Symfony\Component\Config\Collection */
        $config = $dependencyInjectionContainer->get('config');
        $config->add('auth', new Config('auth.yml', $dependencyInjectionContainer->get('config_loader')));
    }
    
    public function dispatch(Request $request, Container $dependencyInjectionContainer)
    {
        if ($request->attributes->get('BOOTSTRAP_ONLY')) {
            return;
        }
        
        //Redirect if page is protected
        $config = $dependencyInjectionContainer->get('config');
        $authInverted = in_array($request->attributes->get('ROUTE'), $config->get('auth.invertAuth', array()));
        $shouldAuth = $config->get('auth.mode') == 'defaultOpen' ? $authInverted : !$authInverted;
        if ($shouldAuth && !$dependencyInjectionContainer->has('user')) {
            header(sprintf('Location: %s', $dependencyInjectionContainer->get('router')->buildRoute($config->get('auth.noAuthRedirect'))->getUrl()));
            exit;
        }
    }
    
    /**
     * Get the module bootstrap priority, from 0 to 10. 0 = ultra high priority
     * (do not use), 10 = very low.
     * @return integer
     */
    public function getBootstrapPriority()
    {
        return 1;
    }
}
