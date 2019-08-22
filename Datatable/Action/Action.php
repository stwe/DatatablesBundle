<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Action;

use Closure;
use Exception;
use Sg\DatatablesBundle\Datatable\HtmlContainerTrait;
use Sg\DatatablesBundle\Datatable\OptionsTrait;
use Sg\DatatablesBundle\Datatable\RenderIfTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Action
{
    use OptionsTrait;

    // Render an Action only if conditions are TRUE.
    use RenderIfTrait;

    /*
     * An Action has a 'start_html' and a 'end_html' option.
     * <startHtml>action</endHtml>
     */
    use HtmlContainerTrait;

    /**
     * The name of the Action route.
     * Default: null.
     *
     * @var string|null
     */
    protected $route;

    /**
     * The route parameters.
     * Default: null.
     *
     * @var array|Closure|null
     */
    protected $routeParameters;

    /**
     * An icon for the Action.
     * Default: null.
     *
     * @var string|null
     */
    protected $icon;

    /**
     * A label for the Action.
     * Default: null.
     *
     * @var string|null
     */
    protected $label;

    /**
     * Show confirm message if true.
     * Default: false.
     *
     * @var bool
     */
    protected $confirm;

    /**
     * The confirm message.
     * Default: null.
     *
     * @var string|null
     */
    protected $confirmMessage;

    /**
     * HTML attributes (except 'href' and 'value').
     * Default: null.
     *
     * @var array|Closure|null
     */
    protected $attributes;

    /**
     * Render a button instead of a link.
     * Default: false.
     *
     * @var bool
     */
    protected $button;

    /**
     * The button value.
     * Default: null.
     *
     * @var string|null
     */
    protected $buttonValue;

    /**
     * Use the Datatable-Name as prefix for the button value.
     * Default: false.
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

    /**
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
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
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
        ]);

        $resolver->setAllowedTypes('route', ['null', 'string']);
        $resolver->setAllowedTypes('route_parameters', ['null', 'array', 'Closure']);
        $resolver->setAllowedTypes('icon', ['null', 'string']);
        $resolver->setAllowedTypes('label', ['null', 'string']);
        $resolver->setAllowedTypes('confirm', 'bool');
        $resolver->setAllowedTypes('confirm_message', ['null', 'string']);
        $resolver->setAllowedTypes('attributes', ['null', 'array', 'Closure']);
        $resolver->setAllowedTypes('button', 'bool');
        $resolver->setAllowedTypes('button_value', ['null', 'string']);
        $resolver->setAllowedTypes('button_value_prefix', 'bool');
        $resolver->setAllowedTypes('render_if', ['null', 'Closure']);
        $resolver->setAllowedTypes('start_html', ['null', 'string']);
        $resolver->setAllowedTypes('end_html', ['null', 'string']);

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return string|null
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param string|null $route
     *
     * @return $this
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return array|Closure|null
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    /**
     * @param array|Closure|null $routeParameters
     *
     * @return $this
     */
    public function setRouteParameters($routeParameters)
    {
        $this->routeParameters = $routeParameters;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string|null $icon
     *
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string|null $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return bool
     */
    public function isConfirm()
    {
        return $this->confirm;
    }

    /**
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
     * @return string|null
     */
    public function getConfirmMessage()
    {
        return $this->confirmMessage;
    }

    /**
     * @param string|null $confirmMessage
     *
     * @return $this
     */
    public function setConfirmMessage($confirmMessage)
    {
        $this->confirmMessage = $confirmMessage;

        return $this;
    }

    /**
     * @return array|Closure|null
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array|Closure|null $attributes
     *
     * @throws Exception
     *
     * @return $this
     */
    public function setAttributes($attributes)
    {
        if (\is_array($attributes)) {
            if (\array_key_exists('href', $attributes)) {
                throw new Exception('Action::setAttributes(): The href attribute is not allowed in this context.');
            }

            if (\array_key_exists('value', $attributes)) {
                throw new Exception('Action::setAttributes(): The value attribute is not allowed in this context.');
            }
        }

        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return bool
     */
    public function isButton()
    {
        return $this->button;
    }

    /**
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
     * @return string|null
     */
    public function getButtonValue()
    {
        return $this->buttonValue;
    }

    /**
     * @param string|null $buttonValue
     *
     * @return $this
     */
    public function setButtonValue($buttonValue)
    {
        $this->buttonValue = $buttonValue;

        return $this;
    }

    /**
     * @return bool
     */
    public function isButtonValuePrefix()
    {
        return $this->buttonValuePrefix;
    }

    /**
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
     * @return string
     */
    public function getDatatableName()
    {
        return $this->datatableName;
    }

    /**
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
