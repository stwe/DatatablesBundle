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
        $taggedServices = $container->findTaggedServiceIds('sg.datatable.view');

        foreach ($taggedServices as $id => $tags) {
            @trigger_error("Tagging datatables view services is deprecated. Use 'sg_datatables.view.abstract' as parent service", E_USER_DEPRECATED);

            $def = $container->getDefinition($id);
            $def->addArgument(new Reference('security.authorization_checker'));
            $def->addArgument(new Reference('security.token_storage'));
            $def->addArgument(new Reference('twig'));
            $def->addArgument(new Reference('translator.default'));
            $def->addArgument(new Reference('router'));
            $def->addArgument(new Reference('doctrine.orm.entity_manager'));
            $def->addArgument('%sg_datatables.datatable.templates%');
        }
    }
}
