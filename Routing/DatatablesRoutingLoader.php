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

    const DEFAULT_CONTROLLER = 'SgDatatablesBundle:Crud';

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

    /**
     * @var array
     */
    private $controller;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param string $globalPrefix
     * @param array  $options
     * @param array  $fields
     * @param array  $controller
     */
    public function __construct($globalPrefix, array $options, array $fields, array $controller)
    {
        $this->loaded = false;
        $this->routes = new RouteCollection();
        $this->globalPrefix = $globalPrefix;
        $this->options = $options;
        $this->fields = $fields;
        $this->setController($controller);
    }

    //-------------------------------------------------
    // Private
    //-------------------------------------------------

    /**
     * Set the default crud controller.
     *
     * @param array $controller
     *
     * @return $this
     */
    private function setController(array $controller)
    {
        foreach ($this->options as $alias => $datatable) {
            $this->controller[$alias]['index'] = isset($controller[$alias]['index']) ? $controller[$alias]['index'] : self::DEFAULT_CONTROLLER . ':index';
            $this->controller[$alias]['index_results'] = isset($controller[$alias]['index_results']) ? $controller[$alias]['index_results'] : self::DEFAULT_CONTROLLER . ':indexResults';
            $this->controller[$alias]['create'] = isset($controller[$alias]['create']) ? $controller[$alias]['create'] : self::DEFAULT_CONTROLLER . ':create';
            $this->controller[$alias]['new'] = isset($controller[$alias]['new']) ? $controller[$alias]['new'] : self::DEFAULT_CONTROLLER . ':new';
            $this->controller[$alias]['show'] = isset($controller[$alias]['show']) ? $controller[$alias]['show'] : self::DEFAULT_CONTROLLER . ':show';
            $this->controller[$alias]['edit'] = isset($controller[$alias]['edit']) ? $controller[$alias]['edit'] : self::DEFAULT_CONTROLLER . ':edit';
            $this->controller[$alias]['update'] = isset($controller[$alias]['update']) ? $controller[$alias]['update'] : self::DEFAULT_CONTROLLER . ':update';
            $this->controller[$alias]['delete'] = isset($controller[$alias]['delete']) ? $controller[$alias]['delete'] : self::DEFAULT_CONTROLLER . ':delete';
        }

        return $this;
    }

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
                    array('_controller' => $this->controller[$alias]['index'], 'alias' => $alias, 'datatable' => $datatable),
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
                    array('_controller' => $this->controller[$alias]['index_results'], 'alias' => $alias, 'datatable' => $datatable),
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
                    array('_controller' => $this->controller[$alias]['create'], 'alias' => $alias, 'datatable' => $datatable, 'fields' => $this->fields),
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
                    array('_controller' => $this->controller[$alias]['new'], 'alias' => $alias, 'datatable' => $datatable, 'fields' => $this->fields),
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
                    array('_controller' => $this->controller[$alias]['show'], 'alias' => $alias, 'datatable' => $datatable, 'fields' => $this->fields),
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
                    array('_controller' => $this->controller[$alias]['edit'], 'alias' => $alias, 'datatable' => $datatable, 'fields' => $this->fields),
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
                    array('_controller' => $this->controller[$alias]['update'], 'alias' => $alias, 'datatable' => $datatable, 'fields' => $this->fields),
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
                    array('_controller' => $this->controller[$alias]['delete'], 'alias' => $alias, 'datatable' => $datatable),
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
