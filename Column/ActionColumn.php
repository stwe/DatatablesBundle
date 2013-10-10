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
     * Default icon
     *
     * @var string
     */
    const DEFAULT_ICON = 'icon-th';

    /**
     * Default show icon.
     *
     * @var string
     */
    const DEFAULT_SHOW_ICON = 'icon-eye-open';

    /**
     * Default edit icon.
     *
     * @var string
     */
    const DEFAULT_EDIT_ICON = 'icon-edit';

    /**
     * Default delete icon.
     *
     * @var string
     */
    const DEFAULT_DELETE_ICON = 'icon-trash';

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
     * @param mixed $mData The column mData
     */
    public function __construct($mData = null)
    {
        parent::__construct(null);

        // your own logic

        $this->route = null;
        $this->routeParameters = array();
        $this->icon = null;
        $this->label = null;
        $this->class = null;
        $this->attributes = array();
    }


    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
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
     * @return null|string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param string $parameter  The route parameter
     * @param string $columnName The name of the column
     *
     * @return $this
     */
    public function addRouteParameter($parameter, $columnName)
    {
        $this->routeParameters[$parameter] = $columnName;

        return $this;
    }

    /**
     * @return array
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    /**
     * @param null|string $icon
     *
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        if (null === $this->class) {
            $this->class = 'btn btn-mini';
        }

        return $this;
    }

    /**
     * @return null|string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
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
     * @return null|string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
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
     * @return null|string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $attribute The attribute
     * @param string $value     The value of the attribute
     *
     * @return $this
     */
    public function addAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}