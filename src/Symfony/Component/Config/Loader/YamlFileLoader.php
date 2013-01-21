<?php

namespace Symfony\Component\Config\Loader;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Loader\FileLoader;

/**
 * Yaml file loader
 */
class YamlFileLoader extends FileLoader
{
    /**
     * Returns the arrays loaded from the Yaml files
     * @param string $resource File path
     * @param string $type
     * @return String[][] Array of arrays containing the configs loaded in files
     * found in the locator paths
     */
    public function load($resource, $type = null)
    {
        $configs = array();
        foreach ($this->locator->locate($resource, null, false) as $aFile) {
            $configs[] = Yaml::parse($aFile);
        }
        
        return $configs;
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }

}