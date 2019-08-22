<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Extension;

use Sg\DatatablesBundle\Datatable\OptionsTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Button
{
    use OptionsTrait;

    //-------------------------------------------------
    // DataTables - Buttons Extension - buttons.buttons
    //-------------------------------------------------

    /**
     * Function describing the action to take on activation.
     *
     * @var array|null
     */
    protected $action;

    /**
     * Ensure that any requirements have been satisfied before initialising a button.
     *
     * @var array|null
     */
    protected $available;

    /**
     * Button class name.
     *
     * @var string|null
     */
    protected $className;

    /**
     * Function that is called when the button is destroyed.
     *
     * @var array|null
     */
    protected $destroy;

    /**
     * Initial enabled state.
     *
     * @var bool|null
     */
    protected $enabled;

    /**
     * Based extends object.
     *
     * @var string|null
     */
    protected $extend;

    /**
     * Button initialisation callback function.
     *
     * @var array|null
     */
    protected $init;

    /**
     * Key activation configuration.
     *
     * @var string|null
     */
    protected $key;

    /**
     * Button name for use in selectors.
     *
     * @var string|null
     */
    protected $name;

    /**
     * Unique namespace for every button.
     *
     * @var string|null
     */
    protected $namespace;

    /**
     * Visible text.
     *
     * @var string|null
     */
    protected $text;

    /**
     * Button title attribute text.
     *
     * @var string|null
     */
    protected $titleAttr;

    //-------------------------------------------------
    // All special button options
    //-------------------------------------------------

    /**
     * Button options.
     *
     * @var array|null
     */
    protected $buttonOptions;

    public function __construct()
    {
        $this->initOptions();
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
            'action' => null,
            'available' => null,
            'class_name' => null,
            'destroy' => null,
            'enabled' => null,
            'extend' => null,
            'init' => null,
            'key' => null,
            'name' => null,
            'namespace' => null,
            'text' => null,
            'title_attr' => null,
            'button_options' => null,
        ]);

        $resolver->setAllowedTypes('action', ['array', 'null']);
        $resolver->setAllowedTypes('available', ['array', 'null']);
        $resolver->setAllowedTypes('class_name', ['string', 'null']);
        $resolver->setAllowedTypes('destroy', ['array', 'null']);
        $resolver->setAllowedTypes('enabled', ['bool', 'null']);
        $resolver->setAllowedTypes('extend', ['string', 'null']);
        $resolver->setAllowedTypes('init', ['array', 'null']);
        $resolver->setAllowedTypes('key', ['string', 'null']);
        $resolver->setAllowedTypes('name', ['string', 'null']);
        $resolver->setAllowedTypes('namespace', ['string', 'null']);
        $resolver->setAllowedTypes('text', ['string', 'null']);
        $resolver->setAllowedTypes('title_attr', ['string', 'null']);
        $resolver->setAllowedTypes('button_options', ['array', 'null']);

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return array|null
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param array|null $action
     *
     * @return $this
     */
    public function setAction($action)
    {
        if (\is_array($action)) {
            $this->validateArrayForTemplateAndOther($action);
        }

        $this->action = $action;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * @param array|null $available
     *
     * @return $this
     */
    public function setAvailable($available)
    {
        if (\is_array($available)) {
            $this->validateArrayForTemplateAndOther($available);
        }

        $this->available = $available;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string|null $className
     *
     * @return $this
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getDestroy()
    {
        return $this->destroy;
    }

    /**
     * @param array|null $destroy
     *
     * @return $this
     */
    public function setDestroy($destroy)
    {
        if (\is_array($destroy)) {
            $this->validateArrayForTemplateAndOther($destroy);
        }

        $this->destroy = $destroy;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool|null $enabled
     *
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExtend()
    {
        return $this->extend;
    }

    /**
     * @param string|null $extend
     *
     * @return $this
     */
    public function setExtend($extend)
    {
        $this->extend = $extend;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getInit()
    {
        return $this->init;
    }

    /**
     * @param array|null $init
     *
     * @return $this
     */
    public function setInit($init)
    {
        if (\is_array($init)) {
            $this->validateArrayForTemplateAndOther($init);
        }

        $this->init = $init;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string|null $key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string|null $namespace
     *
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitleAttr()
    {
        return $this->titleAttr;
    }

    /**
     * @param string|null $titleAttr
     *
     * @return $this
     */
    public function setTitleAttr($titleAttr)
    {
        $this->titleAttr = $titleAttr;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getButtonOptions()
    {
        return $this->buttonOptions;
    }

    /**
     * @param array|null $buttonOptions
     *
     * @return $this
     */
    public function setButtonOptions($buttonOptions)
    {
        $this->buttonOptions = $buttonOptions;

        return $this;
    }
}
