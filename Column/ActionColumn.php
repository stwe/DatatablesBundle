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
 * @package Sg\DatatablesBundle\Column
 */
class ActionColumn extends BaseColumn
{
    /**
     * @var null|string
     */
    private $route;

    /**
     * @var array
     */
    private $routeParameters;

    /**
     * @var null|string
     */
    private $icon;

    /**
     * @var null|string
     */
    private $label;

    /**
     * @var array
     */
    private $attributes;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param null|string $property An entity's property
     */
    public function __construct($property = null)
    {
        parent::__construct(null);

        $this->setMData(null);
        $this->setBSearchable(false);
        $this->setBSortable(false);

        $this->route = null;
        $this->routeParameters = array();
        $this->icon = null;
        $this->label = null;
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
        $this->routeParameters = $parameters;

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
     * Set attributes.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;

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