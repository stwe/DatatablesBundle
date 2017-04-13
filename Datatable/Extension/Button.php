<?php

/**
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

/**
 * Class Button
 *
 * @package Sg\DatatablesBundle\Datatable\Extension
 */
class Button
{
    /**
     * Use the OptionsResolver.
     */
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

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Button constructor.
     */
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
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
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
        ));

        $resolver->setAllowedTypes('action', array('array', 'null'));
        $resolver->setAllowedTypes('available', array('array', 'null'));
        $resolver->setAllowedTypes('class_name', array('string', 'null'));
        $resolver->setAllowedTypes('destroy', array('array', 'null'));
        $resolver->setAllowedTypes('enabled', array('bool', 'null'));
        $resolver->setAllowedTypes('extend', array('string', 'null'));
        $resolver->setAllowedTypes('init', array('array', 'null'));
        $resolver->setAllowedTypes('key', array('string', 'null'));
        $resolver->setAllowedTypes('name', array('string', 'null'));
        $resolver->setAllowedTypes('namespace', array('string', 'null'));
        $resolver->setAllowedTypes('text', array('string', 'null'));
        $resolver->setAllowedTypes('title_attr', array('string', 'null'));
        $resolver->setAllowedTypes('button_options', array('array', 'null'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get action.
     *
     * @return array|null
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set action.
     *
     * @param array|null $action
     *
     * @return $this
     */
    public function setAction($action)
    {
        if (is_array($action)) {
            $this->validateArrayForTemplateAndOther($action);
        }

        $this->action = $action;

        return $this;
    }

    /**
     * Get available.
     *
     * @return array|null
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * Set available.
     *
     * @param array|null $available
     *
     * @return $this
     */
    public function setAvailable($available)
    {
        if (is_array($available)) {
            $this->validateArrayForTemplateAndOther($available);
        }

        $this->available = $available;

        return $this;
    }

    /**
     * Get className.
     *
     * @return null|string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Set className.
     *
     * @param null|string $className
     *
     * @return $this
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get destroy.
     *
     * @return array|null
     */
    public function getDestroy()
    {
        return $this->destroy;
    }

    /**
     * Set destroy.
     *
     * @param array|null $destroy
     *
     * @return $this
     */
    public function setDestroy($destroy)
    {
        if (is_array($destroy)) {
            $this->validateArrayForTemplateAndOther($destroy);
        }

        $this->destroy = $destroy;

        return $this;
    }

    /**
     * Get enabled.
     *
     * @return bool|null
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set enabled.
     *
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
     * Get extend.
     *
     * @return null|string
     */
    public function getExtend()
    {
        return $this->extend;
    }

    /**
     * Set extend.
     *
     * @param null|string $extend
     *
     * @return $this
     */
    public function setExtend($extend)
    {
        $this->extend = $extend;

        return $this;
    }

    /**
     * Get init.
     *
     * @return array|null
     */
    public function getInit()
    {
        return $this->init;
    }

    /**
     * Set init.
     *
     * @param array|null $init
     *
     * @return $this
     */
    public function setInit($init)
    {
        if (is_array($init)) {
            $this->validateArrayForTemplateAndOther($init);
        }

        $this->init = $init;

        return $this;
    }

    /**
     * Get key.
     *
     * @return null|string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set key.
     *
     * @param null|string $key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get name.
     *
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param null|string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get namespace.
     *
     * @return null|string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Set namespace.
     *
     * @param null|string $namespace
     *
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Get text.
     *
     * @return null|string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set text.
     *
     * @param null|string $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get titleAttr.
     *
     * @return null|string
     */
    public function getTitleAttr()
    {
        return $this->titleAttr;
    }

    /**
     * Set titleAttr.
     *
     * @param null|string $titleAttr
     *
     * @return $this
     */
    public function setTitleAttr($titleAttr)
    {
        $this->titleAttr = $titleAttr;

        return $this;
    }

    /**
     * Get buttonOptions.
     *
     * @return array|null
     */
    public function getButtonOptions()
    {
        return $this->buttonOptions;
    }

    /**
     * Set buttonOptions.
     *
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
