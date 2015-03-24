<?php

namespace horses\config;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Loader\FileLoader;

class YamlFileLoader extends FileLoader
{
    /** @inheritdoc */
    public function load($resource, $type = null)
    {
        $configs = array();
        foreach ($this->locator->locate($resource, null, false) as $aFile) {
            $configs[] = Yaml::parse($aFile);
        }
        
        return $configs;
    }

    /** @inheritdoc */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }

}