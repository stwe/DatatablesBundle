<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class DatatableViewPass
 *
 * @package Sg\DatatablesBundle\DependencyInjection\Compiler
 */
class DatatableViewPass implements CompilerPassInterface
{
    /**
     * Process.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds(
            'sg.datatable.view'
        );

        foreach ($taggedServices as $id => $tagAttributes) {
            $def = $container->getDefinition($id);
            $def->addArgument(new Reference('templating'));
            $def->addArgument(new Reference('translator'));
            $def->addArgument(new Reference('router'));
            $def->addArgument('%sg_datatables.default.layout.options%');
        }
    }
}
