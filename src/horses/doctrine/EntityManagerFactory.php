<?php

namespace horses\doctrine;

use horses\config\Collection as ConfigCollection;
use Doctrine\ORM\Tools\Setup as DoctrineSetup;
use Doctrine\ORM\EntityManager;
use horses\config\Config;
use horses\ServerContext;

class EntityManagerFactory
{
    /** @var Config */
    protected $config;
    /** @var ServerContext */
    protected $serverContext;


    /**
     * @param ConfigCollection $configCollection
     */
    public function __construct(ConfigCollection $configCollection, ServerContext $serverContext)
    {
        $this->config = $configCollection->getSection('doctrine');
        $this->serverContext = $serverContext;
    }

    /**
     * @return EntityManager
     * @throws ProxiesNotWritableException
     * @throws \Doctrine\ORM\ORMException
     */
    public function getEntityManager()
    {
        $proxiesDir = $this->serverContext->getPath(ServerContext::DIR_TMP) . '/doctrineProxies';
        !is_dir($proxiesDir) && @mkdir($proxiesDir, 0755);
        if (!is_dir($proxiesDir) || !is_writable($proxiesDir)) {
            throw new ProxiesNotWritableException(sprintf('Doctrine proxies dir does not exists or not writeable: %s', $proxiesDir));
        }
        
        $connConfig = DoctrineSetup::createAnnotationMetadataConfiguration([$this->serverContext->getPath(ServerContext::DIR_SRC)], true);
        $connConfig->setProxyDir($proxiesDir);
        $connConfig->setProxyNamespace('Proxies');

        $conn = [
            'driver' => $this->config->get('doctrine.driver'),
            'host' => $this->config->get('mysql.host'),
            'dbname' => $this->config->get('mysql.dbname'),
            'user' => $this->config->get('mysql.username'),
            'password' => $this->config->get('mysql.password')];

        $em = EntityManager::create($conn, $connConfig);
        if ($logger = $this->config->get('doctrine.logger')) {
            $loggerClass = sprintf('\Doctrine\DBAL\Logging\%s', $logger);
            $em->getConfiguration()->setSQLLogger(new $loggerClass());
        }

        return $em;
    }
}
