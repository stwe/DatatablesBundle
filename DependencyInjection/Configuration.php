<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package Sg\DatatablesBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sg_datatables');

        $this->addDatatableSection($rootNode);
        $this->addSiteSection($rootNode);
        $this->addQuerySection($rootNode);
        $this->addRoutesSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Add datatable section.
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addDatatableSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('datatable')->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('templates')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('base')->defaultValue('SgDatatablesBundle:Datatable:datatable.html.twig')->end()
                                ->scalarNode('html')->defaultValue('SgDatatablesBundle:Datatable:datatable_html.html.twig')->end()
                                ->scalarNode('js')->defaultValue('SgDatatablesBundle:Datatable:datatable_js.html.twig')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Add site section.
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addSiteSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('site')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('title')->defaultValue('SgDatatablesBundle')->end()
                        ->scalarNode('base_layout')->defaultValue('SgDatatablesBundle:Crud:layout.html.twig')->end()
                        ->scalarNode('login_route')->defaultNull()->end()
                        ->scalarNode('logout_route')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Add query section.
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addQuerySection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('query')->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('search_on_non_visible_columns')
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Add routes section.
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addRoutesSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->scalarNode('global_prefix')->defaultValue('admin')->end()
                ->arrayNode('routes')
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('fields')
                    ->useAttributeAsKey('route')
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->arrayNode('show')
                                ->requiresAtLeastOneElement()
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('new')
                                ->requiresAtLeastOneElement()
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('edit')
                                ->requiresAtLeastOneElement()
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('controller')
                    ->useAttributeAsKey('route')
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('index')
                                ->defaultValue('SgDatatablesBundle:Crud:index')
                            ->end()
                            ->scalarNode('index_results')
                                ->defaultValue('SgDatatablesBundle:Crud:indexResults')
                            ->end()
                            ->scalarNode('show')
                                ->defaultValue('SgDatatablesBundle:Crud:show')
                            ->end()
                            ->scalarNode('create')
                                ->defaultValue('SgDatatablesBundle:Crud:create')
                            ->end()
                            ->scalarNode('new')
                                ->defaultValue('SgDatatablesBundle:Crud:new')
                            ->end()
                            ->scalarNode('update')
                                ->defaultValue('SgDatatablesBundle:Crud:update')
                            ->end()
                            ->scalarNode('edit')
                                ->defaultValue('SgDatatablesBundle:Crud:edit')
                            ->end()
                            ->scalarNode('delete')
                                ->defaultValue('SgDatatablesBundle:Crud:delete')
                            ->end()
                        ->end()
                    ->end()

                ->end()
            ->end()
        ;
    }
}
