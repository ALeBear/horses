<?php

namespace horses\plugin\locale;

use horses\IPlugin;
use horses\KernelPanicException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

class Plugin implements IPlugin
{
    public function bootstrap(Request $request, Container $dependencyInjectionContainer)
    {
        $config = $dependencyInjectionContainer->get('config');
        $config->add('locale', new Config('locale.yml', $dependencyInjectionContainer->get('config_loader')));
        
        $mainFilesPath = $request->attributes->get('DIR_APPLICATION');
        if (!is_dir($mainFilesPath)) {
            throw new KernelPanicException(sprintf('Locale dir does not exists or not readable: %s', $mainFilesPath));
        }
        
        //Find lang
        $lang = null;
        $urlLang = explode('/', $request->getPathInfo());
        $urlLang = $urlLang[1];
        foreach ($config->get('locale.available') as $code => $urlPrefix) {
            if ($urlPrefix == $urlLang) {
                $lang = $code;
            }
        }
        if (!$lang) {
            //Try first in cookie, then browser, then default
            if (!($lang = $request->getSession()->get('locale'))) {
                $lang = $request->getPreferredLanguage(array_keys($config->get('locale.available')));
            }
        }
        
        $request->getSession()->set('locale', $lang);
        
        $dependencyInjectionContainer->register('locale', 'horses\\plugin\\locale\\Locale')
            ->addMethodCall('setLang', array($lang))
            ->addMethodCall('injectPath', array($mainFilesPath));
        
        foreach ($config->get('locale.available') as $code => $urlPrefix) {
            if ($code == $lang) {
                if (strpos(strtolower(PHP_OS), 'win') !== false) {
                    switch ($urlPrefix) {
                        case 'fr':
                            $locale = 'french';
                            break;
                        default:
                            $locale = 'english';
                    }
                } else {
                    $locale = $code;
                }
                setlocale(LC_ALL, $locale);
                $dependencyInjectionContainer->get('router')->addPrefix($urlPrefix);
            }
        }
    }
    
    public function dispatch(Request $request, Container $dependencyInjectionContainer)
    {
        $dependencyInjectionContainer->get('locale')
            ->injectPath(sprintf('%s/%s', $request->attributes->get('DIR_CONTROLLERS'), $request->attributes->get('MODULE')))
            ->addDictionaryFilename(sprintf('%s-dict', $request->attributes->get('ACTION')));
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
