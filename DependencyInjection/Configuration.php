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
        $this->addAdminSections($rootNode);

        return $treeBuilder;
    }

    //-------------------------------------------------
    // Datatable section
    //-------------------------------------------------

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
                    ->children()
                        ->arrayNode('query')->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('search_on_non_visible_columns')
                                    ->defaultFalse()
                                ->end()
                                ->booleanNode('translation_query_hints')
                                    ->defaultFalse()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    //-------------------------------------------------
    // Admin sections
    //-------------------------------------------------

    /**
     * Add admin sections.
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addAdminSections(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('admin')->addDefaultsIfNotSet()
                    ->append($this->addAdminSiteSection())
                    ->append($this->addEntitiesSection())
                ->end()
            ->end()
        ;
    }

    /**
     * Add admin site section.
     *
     * @return ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addAdminSiteSection()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('site')->addDefaultsIfNotSet();

        $node
            ->children()
                ->scalarNode('title')->defaultValue('SgDatatablesBundle')->end()
                ->scalarNode('base_layout')->defaultValue('SgDatatablesBundle:Crud:layout.html.twig')->end()
                ->scalarNode('admin_route_prefix')->defaultValue('admin')->end()
                ->scalarNode('login_route')->defaultValue('fos_user_security_login')->end()
                ->scalarNode('logout_route')->defaultValue('fos_user_security_logout')->end()
            ->end()
        ;

        return $node;
    }

    /**
     * Add entities section.
     *
     * @return ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addEntitiesSection()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('entities');

        $node
            ->prototype('array')
                ->children()
                    ->scalarNode('class')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('route_prefix')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('datatable')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('label_group')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('label')->isRequired()->cannotBeEmpty()->end()
                    ->arrayNode('heading')
                        ->children()
                            ->scalarNode('index')->end()
                            ->scalarNode('show')->end()
                            ->scalarNode('new')->end()
                            ->scalarNode('edit')->end()
                            ->scalarNode('delete')->end()
                        ->end()
                    ->end()
                    ->arrayNode('controller')
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
                    ->arrayNode('fields')
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
                    ->arrayNode('form_types')
                        ->children()
                            ->scalarNode('new')->end()
                            ->scalarNode('edit')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
