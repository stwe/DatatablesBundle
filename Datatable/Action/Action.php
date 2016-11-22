<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Action;

use Sg\DatatablesBundle\Datatable\OptionsTrait;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Closure;

/**
 * Class Action
 *
 * @package Sg\DatatablesBundle\Datatable\Action
 */
class Action
{
    use OptionsTrait;

    /**
     * The name of the Action route.
     * A required option.
     *
     * @var string
     */
    protected $route;

    /**
     * The route parameters.
     * Default: null
     *
     * @var null|array
     */
    protected $routeParameters;

    /**
     * An icon for the Action.
     * Default: null
     *
     * @var null|string
     */
    protected $icon;

    /**
     * A label for the Action.
     * Default: null
     *
     * @var null|string
     */
    protected $label;

    /**
     * Show confirm message if true.
     * Default: false
     *
     * @var bool
     */
    protected $confirm;

    /**
     * The confirm message.
     * Default: null
     *
     * @var null|string
     */
    protected $confirmMessage;

    /**
     * HTML <a> Tag attributes (except 'href').
     * Default: null
     *
     * @var null|array
     */
    protected $attributes;

    /**
     * Add Action only if parameter / conditions are TRUE.
     * Default: null
     *
     * @var null|Closure
     */
    protected $addIf;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Action constructor.
     */
    public function __construct()
    {
        $this->initOptions(false);
    }

    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * Config options.
     *
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('route'));

        $resolver->setDefaults(array(
            'route_parameters' => null,
            'icon' => null,
            'label' => null,
            'confirm' => false,
            'confirm_message' => null,
            'attributes' => null,
            'add_if' => null
        ));

        $resolver->setAllowedTypes('route', 'string');
        $resolver->setAllowedTypes('route_parameters', array('null', 'array'));
        $resolver->setAllowedTypes('icon', array('null', 'string'));
        $resolver->setAllowedTypes('label', array('null', 'string'));
        $resolver->setAllowedTypes('confirm', 'bool');
        $resolver->setAllowedTypes('confirm_message', array('null', 'string'));
        $resolver->setAllowedTypes('attributes', array('null', 'array'));
        $resolver->setAllowedTypes('add_if', array('null', 'Closure'));

        return $this;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Checks whether the Action may be added.
     *
     * @return bool
     */
    public function callAddIfClosure()
    {
        if ($this->addIf instanceof Closure) {
            return call_user_func($this->addIf);
        }

        return true;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

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
     * Get routeParameters.
     *
     * @return null|array
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    /**
     * Set routeParameters.
     *
     * @param null|array $routeParameters
     *
     * @return $this
     */
    public function setRouteParameters($routeParameters)
    {
        $this->routeParameters = $routeParameters;

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
     * Get label.
     *
     * @return null|string
     */
    public function getLabel()
    {
        return $this->label;
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
     * Get confirm.
     *
     * @return bool
     */
    public function isConfirm()
    {
        return $this->confirm;
    }

    /**
     * Set confirm.
     *
     * @param bool $confirm
     *
     * @return $this
     */
    public function setConfirm($confirm)
    {
        $this->confirm = $confirm;

        return $this;
    }

    /**
     * Get confirmMessage.
     *
     * @return null|string
     */
    public function getConfirmMessage()
    {
        return $this->confirmMessage;
    }

    /**
     * Set confirmMessage.
     *
     * @param null|string $confirmMessage
     *
     * @return $this
     */
    public function setConfirmMessage($confirmMessage)
    {
        $this->confirmMessage = $confirmMessage;

        return $this;
    }

    /**
     * Get attributes.
     *
     * @return null|array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set attributes.
     *
     * @param null|array $attributes
     *
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Get addIf.
     *
     * @return null|Closure
     */
    public function getAddIf()
    {
        return $this->addIf;
    }

    /**
     * Set addIf.
     *
     * @param null|Closure $addIf
     *
     * @return $this
     */
    public function setAddIf($addIf)
    {
        $this->addIf = $addIf;

        return $this;
    }
}
