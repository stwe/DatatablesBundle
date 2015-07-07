<?php

/**
 * This file is part of the WgUniversalDataTableBundle package.
 *
 * (c) stwe <https://github.com/stwe/DataTablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wg\UniversalDataTable\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package Wg\UniversalDataTable\DependencyInjection
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

        $rootNode
            ->children()
                ->arrayNode('default_layout')->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('templates')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('base')->defaultValue('WgUniversalDataTableBundle:DataTable:datatable.html.twig')->end()
                                ->scalarNode('html')->defaultValue('WgUniversalDataTableBundle:DataTable:datatable_html.html.twig')->end()
                                ->scalarNode('js')->defaultValue('WgUniversalDataTableBundle:DataTable:datatable_js.html.twig')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
