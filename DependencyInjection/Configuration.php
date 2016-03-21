<?php

namespace AJStudio\CentralBankBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ajstudio_central_bank');

        $rootNode
            ->children()
                ->scalarNode('url')
                    ->defaultValue('http://www.cbr.ru/scripts/XML_daily.asp')
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('currencies')
                    ->prototype('scalar')->end()
                ->end()
                ->booleanNode('allow_db_history')->end()
                ->scalarNode('currency_entity')->end()
                ->scalarNode('currency_has_value_entity')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
