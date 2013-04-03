<?php

namespace horses\plugin\main;

use Symfony\Component\Config\ConfigAbstract;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class KernelConfig extends ConfigAbstract
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('kernel')
            ->children()
                ->scalarNode('htdocsDir')->isRequired()->end()
            ->end();

        return $treeBuilder;
    }
}