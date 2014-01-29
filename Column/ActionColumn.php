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
 * @package Sg\DatatablesBundle\Column
 */
class ActionColumn extends BaseColumn
{
    /**
     * The action route.
     *
     * @var null|string
     */
    private $route;

    /**
     * The action route parameters.
     *
     * @var array
     */
    private $routeParameters;

    /**
     * An action icon.
     *
     * @var null|string
     */
    private $icon;

    /**
     * An action label.
     *
     * @var null|string
     */
    private $label;

    /**
     * HTML attributes.
     *
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
     *
     * @throws Exception
     */
    public function __construct($property = null)
    {
        if (null != $property) {
            throw new Exception("The entity's property should be null.");
        }

        parent::__construct($property);
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

        if (array_key_exists('route', $options)) {
            $this->setRoute($options['route']);
        }
        if (array_key_exists('parameters', $options)) {
            $this->setRouteParameters($options['parameters']);
        }
        if (array_key_exists('icon', $options)) {
            $this->setIcon($options['icon']);
        }
        if (array_key_exists('label', $options)) {
            $this->setLabel($options['label']);
        }
        if (array_key_exists('attributes', $options)) {
            $this->setAttributes($options['attributes']);
        }
        /*
        if (array_key_exists('intention', $options)) {
            $this->setIntention($options['intention']);
        }
        */
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaults()
    {
        parent::setDefaults();

        $this->setBSearchable(false);
        $this->setBSortable(false);

        $this->setRoute(null);
        $this->setRouteParameters(array());
        $this->setIcon(null);
        $this->setLabel(null);
        $this->setAttributes(array());
    }


    //-------------------------------------------------
    // Getters && Setters
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