<?php

namespace horses\plugin\doctrine;

use horses\Symfony\ConfigAbstract;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Config extends ConfigAbstract
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('db')
            ->children()
                ->arrayNode('mysql')
                    ->children()
                        ->scalarNode('host')->isRequired()->end()
                        ->scalarNode('username')->isRequired()->end()
                        ->scalarNode('password')->isRequired()->end()
                        ->scalarNode('dbname')->isRequired()->end()
                    ->end()
                ->end()
                ->arrayNode('doctrine')
                    ->children()
                        ->scalarNode('driver')->isRequired()->end()
                        ->scalarNode('logger')->defaultValue('')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}