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
    const PREF = 'sg_admin_';

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
    private $adminRoutePrefix;

    /**
     * @var array
     */
    private $entities;

    /**
     * @var array
     */
    private $actions;

    /**
     * @var array
     */
    private $headings;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param string $adminRoutePrefix
     * @param array  $entities
     */
    public function __construct($adminRoutePrefix, array $entities)
    {
        $this->loaded = false;
        $this->routes = new RouteCollection();
        $this->adminRoutePrefix = $adminRoutePrefix;
        $this->entities = $entities;

        $this->actions = array();
        $this->headings = array();

        $this->setActions();
        $this->setHeadings();
    }

    //-------------------------------------------------
    // Private
    //-------------------------------------------------

    /**
     * Set actions.
     *
     * @return $this
     */
    private function setActions()
    {
        foreach ($this->entities as $alias => $options) {
            $alias = strtolower($alias);

            $this->actions[$alias]['index'] = isset($options['controller']['index']) ? $options['controller']['index'] : self::DEFAULT_CONTROLLER . ':index';
            $this->actions[$alias]['index_results'] = isset($options['controller']['index_results']) ? $options['controller']['index_results'] : self::DEFAULT_CONTROLLER . ':indexResults';
            $this->actions[$alias]['create'] = isset($options['controller']['create']) ? $options['controller']['create'] : self::DEFAULT_CONTROLLER . ':create';
            $this->actions[$alias]['new'] = isset($options['controller']['new']) ? $options['controller']['new'] : self::DEFAULT_CONTROLLER . ':new';
            $this->actions[$alias]['show'] = isset($options['controller']['show']) ? $options['controller']['show'] : self::DEFAULT_CONTROLLER . ':show';
            $this->actions[$alias]['edit'] = isset($options['controller']['edit']) ? $options['controller']['edit'] : self::DEFAULT_CONTROLLER . ':edit';
            $this->actions[$alias]['update'] = isset($options['controller']['update']) ? $options['controller']['update'] : self::DEFAULT_CONTROLLER . ':update';
            $this->actions[$alias]['delete'] = isset($options['controller']['delete']) ? $options['controller']['delete'] : self::DEFAULT_CONTROLLER . ':delete';
        }

        return $this;
    }

    /**
     * Set headings.
     *
     * @return $this
     */
    private function setHeadings()
    {
        foreach ($this->entities as $alias => $options) {
            $alias = strtolower($alias);

            $this->headings[$alias]['index'] = isset($options['heading']['index']) ? $options['heading']['index'] : '';
            $this->headings[$alias]['show'] = isset($options['heading']['show']) ? $options['heading']['show'] : '';
            $this->headings[$alias]['new'] = isset($options['heading']['new']) ? $options['heading']['new'] : '';
            $this->headings[$alias]['edit'] = isset($options['heading']['edit']) ? $options['heading']['edit'] : '';
            $this->headings[$alias]['delete'] = isset($options['heading']['delete']) ? $options['heading']['delete'] : '';
        }

        return $this;
    }

    /**
     * Generates routes.
     */
    private function generatesRoutes()
    {
        foreach ($this->entities as $alias => $options) {
            $alias = strtolower($alias);

            // index
            $this->routes->add(
                DatatablesRoutingLoader::PREF . $alias . '_index',
                new Route(
                    '/' . $options['route_prefix'] . '/',
                    array('_controller' => $this->actions[$alias]['index'], 'datatable' => $options['datatable'], 'heading' => $this->headings[$alias]['index']),
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
                    '/' . $options['route_prefix'] . '/results',
                    array('_controller' => $this->actions[$alias]['index_results'], 'datatable' => $options['datatable']),
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
                    '/' . $options['route_prefix'] . '/',
                    array('_controller' => $this->actions[$alias]['create'], 'alias' => $alias, 'options' => $options),
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
                    '/' . $options['route_prefix'] . '/new',
                    array('_controller' => $this->actions[$alias]['new'], 'alias' => $alias, 'options' => $options, 'heading' => $this->headings[$alias]['new']),
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
                new Route('/' . $options['route_prefix'] . '/{id}',
                    array('_controller' => $this->actions[$alias]['show'], 'alias' => $alias, 'options' => $options, 'heading' => $this->headings[$alias]['show']),
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
                    '/' . $options['route_prefix'] . '/{id}/edit',
                    array('_controller' => $this->actions[$alias]['edit'], 'alias' => $alias, 'options' => $options, 'heading' => $this->headings[$alias]['edit']),
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
                    '/' . $options['route_prefix'] . '/{id}',
                    array('_controller' => $this->actions[$alias]['update'], 'alias' => $alias, 'options' => $options),
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
                    '/' . $options['route_prefix'] . '/{id}',
                    array('_controller' => $this->actions[$alias]['delete'], 'alias' => $alias, 'options' => $options, 'heading' => $this->headings[$alias]['delete']),
                    array('id' => '\d+'),
                    array(),
                    '',
                    array(),
                    array('DELETE')
                )
            );
        }
    }

    private function generateAdminHomeRoute()
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
        $this->generateAdminHomeRoute();
        $this->routes->addPrefix($this->adminRoutePrefix);

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
