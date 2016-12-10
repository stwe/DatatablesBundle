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
use Sg\DatatablesBundle\Datatable\RenderIfTrait;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Exception;

/**
 * Class Action
 *
 * @package Sg\DatatablesBundle\Datatable\Action
 */
class Action
{
    /**
     * Use the OptionsResolver.
     */
    use OptionsTrait;

    /**
     * Render an Action only if parameter / conditions are TRUE.
     */
    use RenderIfTrait;

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
            'render_if' => null
        ));

        $resolver->setAllowedTypes('route', 'string');
        $resolver->setAllowedTypes('route_parameters', array('null', 'array'));
        $resolver->setAllowedTypes('icon', array('null', 'string'));
        $resolver->setAllowedTypes('label', array('null', 'string'));
        $resolver->setAllowedTypes('confirm', 'bool');
        $resolver->setAllowedTypes('confirm_message', array('null', 'string'));
        $resolver->setAllowedTypes('attributes', array('null', 'array'));
        $resolver->setAllowedTypes('render_if', array('null', 'Closure'));

        return $this;
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
     * @throws Exception
     */
    public function setAttributes($attributes)
    {
        if (is_array($attributes)) {
            if (array_key_exists('href', $attributes)) {
                throw new Exception('Action::setAttributes(): The href attribute is not supported.');
            }
        }

        $this->attributes = $attributes;

        return $this;
    }
}
