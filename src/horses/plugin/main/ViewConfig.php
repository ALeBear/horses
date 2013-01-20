<?php

namespace horses\plugin\main;

use Symfony\Component\Config\ConfigAbstract;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class ViewConfig extends ConfigAbstract
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('view')
            ->children()
                ->arrayNode('layout')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('meta')
                    ->prototype('scalar')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}