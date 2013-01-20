<?php

namespace horses\plugin\auth;

use Symfony\Component\Config\ConfigAbstract;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Config extends ConfigAbstract
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('auth')
            ->children()
                ->scalarNode('userClassname')->isRequired()->end()
                ->scalarNode('noAuthRedirect')->isRequired()->end()
                ->arrayNode('disableAuth')
                    ->prototype('scalar')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}