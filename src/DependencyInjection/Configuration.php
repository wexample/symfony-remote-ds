<?php

namespace Wexample\SymfonyRemoteDs\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Wexample\SymfonyHelpers\Helper\RoleHelper;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('wexample_symfony_remote_ds');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('access_role')
                    ->defaultValue(RoleHelper::ROLE_ADMIN)
                    ->info('Role required to open the remotes screen and run its checks, each of which reaches out of the app.')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
