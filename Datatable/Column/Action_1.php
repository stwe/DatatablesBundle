<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Column;

/**
 * Class Action
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class Action
{
    /**
     * The route to the action.
     *
     * @var string
     */
    protected $route;

    /**
     * The action route parameters.
     *
     * @var array
     */
    protected $routeParameters;

    /**
     * An action icon.
     *
     * @var string
     */
    protected $icon;

    /**
     * An action label.
     *
     * @var string
     */
    protected $label;

    /**
     * Show confirm message if true.
     *
     * @var boolean
     */
    protected $confirm;

    /**
     * The confirm message.
     *
     * @var string
     */
    protected $confirmMessage;

    /**
     * HTML attributes.
     *
     * @var array
     */
    protected $attributes;

    /**
     * Check the specified role.
     *
     * @var string
     */
    protected $role;

    /**
     * Render only if parameter / conditions are TRUE
     *
     * @var array
     */
    protected $renderConditions;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->route = "";
        $this->routeParameters = array();
        $this->icon = "";
        $this->label = "";
        $this->confirm = false;
        $this->confirmMessage = "";
        $this->attributes = array();
        $this->role = "";
        $this->renderConditions = array();
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set options.
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options)
    {
        if (array_key_exists("route", $options)) {
            $this->setRoute($options["route"]);
        }
        if (array_key_exists("route_parameters", $options)) {
            $this->setRouteParameters($options["route_parameters"]);
        }
        if (array_key_exists("icon", $options)) {
            $this->setIcon($options["icon"]);
        }
        if (array_key_exists("label", $options)) {
            $this->setLabel($options["label"]);
        }
        if (array_key_exists("confirm", $options)) {
            $this->setConfirm($options["confirm"]);
        }
        if (array_key_exists("confirm_message", $options)) {
            $this->setConfirmMessage($options["confirm_message"]);
        }
        if (array_key_exists("attributes", $options)) {
            $this->setAttributes($options["attributes"]);
        }
        if (array_key_exists("role", $options)) {
            $this->setRole($options["role"]);
        }
        if (array_key_exists("renderif", $options)) {
            $this->setRenderConditions($options["renderif"]);
        }

        return $this;
    }

    /**
     * Set route.
     *
     * @param string $route
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
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set route parameters.
     *
     * @param array $routeParameters
     *
     * @return $this
     */
    public function setRouteParameters(array $routeParameters)
    {
        $this->routeParameters = $routeParameters;

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
     * @param string $icon
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
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set label.
     *
     * @param string $label
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
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set confirm.
     *
     * @param boolean $confirm
     *
     * @return $this
     */
    public function setConfirm($confirm)
    {
        $this->confirm = (boolean) $confirm;

        return $this;
    }

    /**
     * Get confirm.
     *
     * @return boolean
     */
    public function getConfirm()
    {
        return (boolean) $this->confirm;
    }

    /**
     * Set confirm message.
     *
     * @param string $confirmMessage
     *
     * @return $this
     */
    public function setConfirmMessage($confirmMessage)
    {
        $this->confirmMessage = $confirmMessage;

        return $this;
    }

    /**
     * Get confirm message.
     *
     * @return string
     */
    public function getConfirmMessage()
    {
        return $this->confirmMessage;
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

    /**
     * Set role.
     *
     * @param string $role
     *
     * @return $this
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role.
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set render conditions.
     *
     * @param array $renderConditions
     *
     * @return $this
     */
    public function setRenderConditions(array $renderConditions)
    {
        $this->renderConditions = $renderConditions;

        return $this;
    }

    /**
     * Get render conditions.
     *
     * @return array
     */
    public function getRenderConditions()
    {
        return $this->renderConditions;
    }
}
