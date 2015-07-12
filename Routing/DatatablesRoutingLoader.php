<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use RuntimeException;

/**
 * Class DatatablesRoutingLoader
 *
 * @package Sg\DatatablesBundle\Routing
 */
class DatatablesRoutingLoader extends Loader
{
    const PREF = 'sg_';

    /**
     * @var boolean
     */
    private $loaded;

    /**
     * @var RouteCollection
     */
    private $routes;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $fields;

    /**
     * @var array
     */
    private $roles;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param array $options
     * @param array $fields
     * @param array $roles
     */
    public function __construct(array $options, array $fields, array $roles)
    {
        $this->loaded = false;
        $this->routes = new RouteCollection();
        $this->options = $options;
        $this->fields = $fields;
        $this->roles = $roles;
    }

    //-------------------------------------------------
    // Private
    //-------------------------------------------------

    /**
     * Generates routes.
     */
    private function generatesRoutes()
    {
        foreach ($this->options as $alias => $datatable) {
            // index
            $this->routes->add(
                DatatablesRoutingLoader::PREF . $alias . '_index',
                new Route(
                    '/' . $alias . '/',
                    array('_controller' => 'SgDatatablesBundle:Crud:index', 'alias' => $alias, 'datatable' => $datatable, 'roles' => $this->roles),
                    array(),
                    array('expose' => true),
                    '',
                    array(),
                    array('GET')
                )
            );
            $this->routes->add(
                DatatablesRoutingLoader::PREF . $alias . '_results',
                new Route(
                    '/' . $alias . '/results',
                    array('_controller' => 'SgDatatablesBundle:Crud:indexResults', 'alias' => $alias, 'datatable' => $datatable, 'roles' => $this->roles),
                    array(),
                    array(),
                    '',
                    array(),
                    array('GET')
                )
            );

            // new
            $this->routes->add(
                DatatablesRoutingLoader::PREF . $alias . '_create',
                new Route(
                    '/' . $alias . '/',
                    array('_controller' => 'SgDatatablesBundle:Crud:create', 'alias' => $alias, 'datatable' => $datatable, 'fields' => $this->fields, 'roles' => $this->roles),
                    array(),
                    array(),
                    '',
                    array(),
                    array('POST')
                )
            );
            $this->routes->add(
                DatatablesRoutingLoader::PREF . $alias . '_new',
                new Route(
                    '/' . $alias . '/new',
                    array('_controller' => 'SgDatatablesBundle:Crud:new', 'alias' => $alias, 'datatable' => $datatable, 'fields' => $this->fields, 'roles' => $this->roles),
                    array(),
                    array('expose' => true),
                    '',
                    array(),
                    array('GET')
                )
            );

            // show
            $this->routes->add(
                DatatablesRoutingLoader::PREF . $alias . '_show',
                new Route('/' . $alias . '/{id}',
                    array('_controller' => 'SgDatatablesBundle:Crud:show', 'alias' => $alias, 'datatable' => $datatable, 'fields' => $this->fields, 'roles' => $this->roles),
                    array('id' => '\d+'),
                    array('expose' => true),
                    '',
                    array(),
                    array('GET')
                )
            );

            // edit
            $this->routes->add(
                DatatablesRoutingLoader::PREF . $alias . '_edit',
                new Route(
                    '/' . $alias . '/{id}/edit',
                    array('_controller' => 'SgDatatablesBundle:Crud:edit', 'alias' => $alias, 'datatable' => $datatable, 'fields' => $this->fields, 'roles' => $this->roles),
                    array('id' => '\d+'),
                    array('expose' => true),
                    '',
                    array(),
                    array('GET')
                )
            );
            $this->routes->add(
                DatatablesRoutingLoader::PREF . $alias . '_update',
                new Route(
                    '/' . $alias . '/{id}',
                    array('_controller' => 'SgDatatablesBundle:Crud:update', 'alias' => $alias, 'datatable' => $datatable, 'fields' => $this->fields, 'roles' => $this->roles),
                    array('id' => '\d+'),
                    array(),
                    '',
                    array(),
                    array('PUT')
                )
            );

            // delete
            $this->routes->add(
                DatatablesRoutingLoader::PREF . $alias . '_delete',
                new Route(
                    '/' . $alias . '/{id}',
                    array('_controller' => 'SgDatatablesBundle:Crud:delete', 'alias' => $alias, 'datatable' => $datatable, 'roles' => $this->roles),
                    array('id' => '\d+'),
                    array(),
                    '',
                    array(),
                    array('DELETE')
                )
            );
        }
    }

    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new RuntimeException('load(): Do not add this loader twice.');
        }

        $this->generatesRoutes();

        return $this->routes;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return 'sg_datatables_routing' === $type;
    }
} 
