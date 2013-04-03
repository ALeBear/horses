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
                ->enumNode('mode')->values(array('defaultProtected', 'defaultOpen'))->isRequired()->end()
                ->scalarNode('noAuthRedirect')->isRequired()->end()
                ->arrayNode('invertAuth')
                    ->prototype('scalar')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}