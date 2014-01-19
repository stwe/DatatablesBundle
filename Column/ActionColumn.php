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

use Exception;

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
     * @throws Exception
     */
    public function setLabel($label)
    {
        if (true === is_array($label)) {
            if ( !(array_key_exists('label', $label) && array_key_exists('translation_domain', $label)) ) {
                throw new Exception('A label and a translation_domain expected.');
            }
        }

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