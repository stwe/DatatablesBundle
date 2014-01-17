<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Column;

use Sg\DatatablesBundle\Column\AbstractColumn as BaseColumn;

/**
 * Class ActionColumn
 *
 * A thanks goes to Alexander Janssen (dutchbridge) for the inspiration:
 *     https://github.com/dutchbridge/DatatableBundle/blob/master/Datatable/Action.php
 *
 * @package Sg\DatatablesBundle\Column
 */
class ActionColumn extends BaseColumn
{
    /**
     * @var null|string
     */
    protected $route;

    /**
     * @var array
     */
    protected $routeParameters;

    /**
     * @var null|string
     */
    protected $icon;

    /**
     * @var null|string
     */
    protected $label;

    /**
     * @var null|string
     */
    protected $class;

    /**
     * @var array
     */
    protected $attributes;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param string $name
     */
    public function __construct($name = null)
    {
        parent::__construct(null);

        $this->mData = null;
        $this->bSearchable = false;
        $this->bSortable = false;

        $this->route = null;
        $this->routeParameters = array();
        $this->icon = null;
        $this->label = null;
        $this->class = null;
        $this->attributes = array();
    }


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'action';
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);

        if (isset($options['route'])) {
            $this->setRoute($options['route']);
        }
        if (isset($options['parameters'])) {
            $this->setRouteParameters($options['parameters']);
        }
        if (isset($options['icon'])) {
            $this->setIcon($options['icon']);
        }
        if (isset($options['label'])) {
            $this->setLabel($options['label']);
        }
        if (isset($options['attributes'])) {
            $this->setAttributes($options['attributes']);
        }
    }


    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * Set route.
     *
     * @param null|string $route
     *
     * @return $this
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route.
     *
     * @return null|string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set route parameters.
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function setRouteParameters(array $parameters)
    {
        foreach ($parameters as $key => $value) {
            $this->routeParameters[$key] = $value;
        }

        return $this;
    }

    /**
     * Get route parameters.
     *
     * @return array
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    /**
     * Set icon.
     *
     * @param null|string $icon
     *
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        if (null === $this->class) {
            $this->class = 'btn btn-default btn-xs';
        }

        return $this;
    }

    /**
     * Get icon.
     *
     * @return null|string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set label.
     *
     * @param null|string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label.
     *
     * @return null|string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set class.
     *
     * @param null|string $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class.
     *
     * @return null|string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set attributes.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }

        return $this;
    }

    /**
     * Get attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}