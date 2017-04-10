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

use Sg\DatatablesBundle\Datatable\HtmlContainerTrait;
use Sg\DatatablesBundle\Datatable\OptionsTrait;
use Sg\DatatablesBundle\Datatable\RenderIfTrait;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Exception;
use Closure;

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
     * Render an Action only if conditions are TRUE.
     */
    use RenderIfTrait;

    /**
     * An Action has a 'start_html' and a 'end_html' option.
     * <startHtml>action</endHtml>
     */
    use HtmlContainerTrait;

    /**
     * The name of the Action route.
     * Default: null
     *
     * @var null|string
     */
    protected $route;

    /**
     * The route parameters.
     * Default: null
     *
     * @var null|array|Closure
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
     * HTML attributes (except 'href' and 'value').
     * Default: null
     *
     * @var null|array
     */
    protected $attributes;

    /**
     * Render a button instead of a link.
     * Default: false
     *
     * @var bool
     */
    protected $button;

    /**
     * The button value.
     * Default: null
     *
     * @var null|string
     */
    protected $buttonValue;

    /**
     * Use the Datatable-Name as prefix for the button value.
     * Default: false
     *
     * @var bool
     */
    protected $buttonValuePrefix;

    /**
     * The name of the associated Datatable.
     *
     * @var string
     */
    protected $datatableName;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Action constructor.
     *
     * @param string $datatableName
     */
    public function __construct($datatableName)
    {
        $this->initOptions();
        $this->datatableName = $datatableName;
    }

    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'route' => null,
            'route_parameters' => null,
            'icon' => null,
            'label' => null,
            'confirm' => false,
            'confirm_message' => null,
            'attributes' => null,
            'button' => false,
            'button_value' => null,
            'button_value_prefix' => false,
            'render_if' => null,
            'start_html' => null,
            'end_html' => null,
        ));

        $resolver->setAllowedTypes('route', array('null', 'string'));
        $resolver->setAllowedTypes('route_parameters', array('null', 'array', 'Closure'));
        $resolver->setAllowedTypes('icon', array('null', 'string'));
        $resolver->setAllowedTypes('label', array('null', 'string'));
        $resolver->setAllowedTypes('confirm', 'bool');
        $resolver->setAllowedTypes('confirm_message', array('null', 'string'));
        $resolver->setAllowedTypes('attributes', array('null', 'array'));
        $resolver->setAllowedTypes('button', 'bool');
        $resolver->setAllowedTypes('button_value', array('null', 'string'));
        $resolver->setAllowedTypes('button_value_prefix', 'bool');
        $resolver->setAllowedTypes('render_if', array('null', 'Closure'));
        $resolver->setAllowedTypes('start_html', array('null', 'string'));
        $resolver->setAllowedTypes('end_html', array('null', 'string'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

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
     * Get routeParameters.
     *
     * @return null|array|Closure
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    /**
     * Set routeParameters.
     *
     * @param null|array|Closure $routeParameters
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
                throw new Exception('Action::setAttributes(): The href attribute is not allowed in this context.');
            }

            if (array_key_exists('value', $attributes)) {
                throw new Exception('Action::setAttributes(): The value attribute is not allowed in this context.');
            }
        }

        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Get button.
     *
     * @return bool
     */
    public function isButton()
    {
        return $this->button;
    }

    /**
     * Set button.
     *
     * @param bool $button
     *
     * @return $this
     */
    public function setButton($button)
    {
        $this->button = $button;

        return $this;
    }

    /**
     * Get buttonValue.
     *
     * @return null|string
     */
    public function getButtonValue()
    {
        return $this->buttonValue;
    }

    /**
     * Set buttonValue.
     *
     * @param null|string $buttonValue
     *
     * @return $this
     */
    public function setButtonValue($buttonValue)
    {
        $this->buttonValue = $buttonValue;

        return $this;
    }

    /**
     * Get buttonValuePrefix.
     *
     * @return bool
     */
    public function isButtonValuePrefix()
    {
        return $this->buttonValuePrefix;
    }

    /**
     * Set buttonValuePrefix.
     *
     * @param bool $buttonValuePrefix
     *
     * @return $this
     */
    public function setButtonValuePrefix($buttonValuePrefix)
    {
        $this->buttonValuePrefix = $buttonValuePrefix;

        return $this;
    }

    /**
     * Get datatableName.
     *
     * @return string
     */
    public function getDatatableName()
    {
        return $this->datatableName;
    }

    /**
     * Set datatableName.
     *
     * @param string $datatableName
     *
     * @return $this
     */
    public function setDatatableName($datatableName)
    {
        $this->datatableName = $datatableName;

        return $this;
    }
}
