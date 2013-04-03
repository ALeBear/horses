<?php

namespace horses\plugin\doctrine;

use Doctrine\ORM\Tools\Setup as DoctrineSetup;
use Doctrine\ORM\EntityManager;
use horses\IPlugin;
use horses\KernelPanicException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;

class Plugin implements IPlugin
{
    public function bootstrap(Request $request, Container $dependencyInjectionContainer)
    {
        $proxiesDir = $request->attributes->get('DIR_APPLICATION') . '/doctrineProxies';
        !is_dir($proxiesDir) && @mkdir($proxiesDir, 0755);
        if (!is_dir($proxiesDir) || !is_writable($proxiesDir)) {
            throw new KernelPanicException(sprintf('Doctrine proxies dir does not exists or not writeable: %s', $proxiesDir));
        }
        
        /* @var $config Symfony\Component\Config\Collection */
        $config = $dependencyInjectionContainer->get('config');
        $config->add('db', new Config('db.yml', $dependencyInjectionContainer->get('config_loader')));

        $connConfig = DoctrineSetup::createAnnotationMetadataConfiguration(array($request->attributes->get('DIR_LIB')), true);
        $connConfig->setProxyDir($proxiesDir);
        $connConfig->setProxyNamespace('Proxies');

        $conn = array(
            'driver' => $config->get('db.doctrine.driver'),
            'host' => $config->get('db.mysql.host'),
            'dbname' => $config->get('db.mysql.dbname'),
            'user' => $config->get('db.mysql.username'),
            'password' => $config->get('db.mysql.password'));

        $em = EntityManager::create($conn, $connConfig);
        if ($logger = $config->get('db.doctrine.logger')) {
            $loggerClass = sprintf('\Doctrine\DBAL\Logging\%s', $logger);
            $em->getConfiguration()->setSQLLogger(new $loggerClass());
        }

        $dependencyInjectionContainer->set('entity_manager', $em);
        
        //Add DbAuth if it's the Auth class (add user too)
        if ($config->get('auth.authClassname') == 'horses\\plugin\\doctrine\\DbAuth') {
            $dependencyInjectionContainer->register('auth', 'horses\\plugin\\doctrine\\DbAuth')
                ->addMethodCall('injectEntityManager', array(new Reference('entity_manager')))
                ->addMethodCall('injectUserClassname', array($config->get('auth.userClassname')));

            $dependencyInjectionContainer->set('user', $dependencyInjectionContainer->get('auth')->getUserFromSession($request->getSession()));
        }
    }
    
    public function dispatch(Request $request, Container $dependencyInjectionContainer)
    {
    }
    
    /**
     * Get the module bootstrap priority, from 0 to 10. 0 = ultra high priority
     * (do not use), 10 = very low.
     * @return integer
     */
    public function getBootstrapPriority()
    {
        return 2;
    }
}
