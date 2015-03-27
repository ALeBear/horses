<?php

namespace horses\config;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Loader\FileLoader;

class YamlFileLoader extends FileLoader
{
    /** @inheritdoc */
    public function load($resource, $type = null)
    {
        $resource = sprintf('%s.yml', $resource);
        $configs = [];
        try {
            foreach ($this->locator->locate($resource, null, false) as $aFile) {
                $parsed = Yaml::parse($aFile);
                $configs[] = $parsed ? $parsed : [];
            }
        } catch (\InvalidArgumentException $e) {
            $configs[] = [];
        }
        
        return $configs;
    }

    /** @inheritdoc */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }

}
