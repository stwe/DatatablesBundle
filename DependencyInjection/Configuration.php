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

        $rootNode
            ->children()
                ->arrayNode('default_layout')->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('page_length')
                            ->defaultValue(10)
                            ->min(1)
                        ->end()
                        ->booleanNode('server_side')->defaultTrue()->end()
                        ->booleanNode('processing')->defaultTrue()->end()
                        ->booleanNode('multiselect')->defaultFalse()->end()
                        ->booleanNode('individual_filtering')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
