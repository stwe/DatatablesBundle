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
     * @var string
     */
    private $globalPrefix;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $fields;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param string $globalPrefix
     * @param array  $options
     * @param array  $fields
     */
    public function __construct($globalPrefix, array $options, array $fields)
    {
        $this->loaded = false;
        $this->routes = new RouteCollection();
        $this->globalPrefix = $globalPrefix;
        $this->options = $options;
        $this->fields = $fields;
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
                    array('_controller' => 'SgDatatablesBundle:Crud:index', 'alias' => $alias, 'datatable' => $datatable),
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
                    array('_controller' => 'SgDatatablesBundle:Crud:indexResults', 'alias' => $alias, 'datatable' => $datatable),
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
                    array('_controller' => 'SgDatatablesBundle:Crud:create', 'alias' => $alias, 'datatable' => $datatable, 'fields' => $this->fields),
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
                    array('_controller' => 'SgDatatablesBundle:Crud:new', 'alias' => $alias, 'datatable' => $datatable, 'fields' => $this->fields),
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
                    array('_controller' => 'SgDatatablesBundle:Crud:show', 'alias' => $alias, 'datatable' => $datatable, 'fields' => $this->fields),
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
                    array('_controller' => 'SgDatatablesBundle:Crud:edit', 'alias' => $alias, 'datatable' => $datatable, 'fields' => $this->fields),
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
                    array('_controller' => 'SgDatatablesBundle:Crud:update', 'alias' => $alias, 'datatable' => $datatable, 'fields' => $this->fields),
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
                    array('_controller' => 'SgDatatablesBundle:Crud:delete', 'alias' => $alias, 'datatable' => $datatable),
                    array('id' => '\d+'),
                    array(),
                    '',
                    array(),
                    array('DELETE')
                )
            );
        }
    }

    private function generateHomeRoute()
    {
        $this->routes->add(
            DatatablesRoutingLoader::PREF . 'home',
            new Route(
                '/',
                array('_controller' => 'SgDatatablesBundle:Crud:home'),
                array(),
                array(),
                '',
                array(),
                array('GET')
            )
        );
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
        $this->generateHomeRoute();
        $this->routes->addPrefix($this->globalPrefix);

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
