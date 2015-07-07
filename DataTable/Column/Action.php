<?php

/**
 * This file is part of the WgUniversalDataTableBundle package.
 *
 * (c) stwe <https://github.com/stwe/DataTablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wg\UniversalDataTable\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class Action
 *
 * @package Wg\UniversalDataTable\DataTable\Column
 */
class Action implements OptionsInterface
{
    /**
     * Options container.
     *
     * @var array
     */
    protected $options;

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
    protected $renderIf;

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setupOptionsResolver(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);

        $this->setOptions($this->options);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('route'));

        $resolver->setDefaults(array(
            'route_parameters' => array(),
            'icon' => '',
            'label' => '',
            'confirm' => false,
            'confirm_message' => '',
            'attributes' => array(),
            'role' => '',
            'render_if' => array()
        ));

        $resolver->setAllowedTypes('route', 'string');
        $resolver->setAllowedTypes('route_parameters', 'array');
        $resolver->setAllowedTypes('icon', 'string');
        $resolver->setAllowedTypes('label', 'string');
        $resolver->setAllowedTypes('confirm', 'bool');
        $resolver->setAllowedTypes('confirm_message', 'string');
        $resolver->setAllowedTypes('attributes', 'array');
        $resolver->setAllowedTypes('role', 'string');
        $resolver->setAllowedTypes('render_if', 'array');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);

        foreach ($options as $key => $value) {
            $key = Container::camelize($key);
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            } else {
                throw new \Exception('setOptions(): ' . $method . ' invalid method name');
            }
        }

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

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
     * Set renderIf.
     *
     * @param array $renderIf
     *
     * @return $this
     */
    public function setRenderIf(array $renderIf)
    {
        $this->renderIf = $renderIf;

        return $this;
    }

    /**
     * Get renderIf.
     *
     * @return array
     */
    public function getRenderIf()
    {
        return $this->renderIf;
    }
}
