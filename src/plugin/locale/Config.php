<?php

namespace horses\plugin\locale;

use horses\Symfony\ConfigAbstract;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Config extends ConfigAbstract
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('locale')
            ->children()
                ->arrayNode('available')
                    ->isRequired()
                    ->prototype('scalar')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}