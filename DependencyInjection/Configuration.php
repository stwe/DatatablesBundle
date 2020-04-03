<?php

/*
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

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('sg_datatables');
        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('sg_datatables');
        }

        $this->addDatatableSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Add datatable section.
     */
    private function addDatatableSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
            ->arrayNode('datatable')->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('query')->addDefaultsIfNotSet()
            ->children()
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
}
