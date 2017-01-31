<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Editable;

use Sg\DatatablesBundle\Datatable\OptionsTrait;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Closure;

/**
 * Class Editable
 *
 * @package Sg\DatatablesBundle\Datatable\Editable
 */
class Editable
{
    /**
     * Use the OptionsResolver.
     */
    use OptionsTrait;

    //-------------------------------------------------
    // X-editable Options
    //-------------------------------------------------

    /**
     * Value that will be displayed in input if original field value is empty (null|undefined|'').
     * Default: null
     *
     * @var string|null
     */
    protected $defaultValue;

    /**
     * Css class applied when editable text is empty.
     * Default: 'editable-empty'
     *
     * @var string
     */
    protected $emptyClass;

    /**
     * Text shown when element is empty.
     * Default: 'Empty'
     *
     * @var string
     */
    protected $emptyText;

    /**
     * Color used to highlight element after update.
     * Default: '#FFFF80'
     *
     * @var string
     */
    protected $highlight;

    /**
     * Mode of editable, can be 'popup' or 'inline'.
     * Default: 'popup'
     *
     * @var string
     */
    protected $mode;

    /**
     * Name of field. Will be submitted on server. Can be taken from id attribute.
     * Default: null
     *
     * @var string|null
     */
    protected $name;

    /**
     * Primary key of editable object.
     * Default: null
     *
     * @var string|null
     */
    protected $pk;

    /**
     * Type of input. Can be text|textarea|select|date|checklist and more.
     * Default: 'text'
     *
     * @var string
     */
    protected $type;

    //-------------------------------------------------
    // Custom Options
    //-------------------------------------------------

    /**
     * Editable only if conditions are True.
     *
     * @var null|Closure
     */
    protected $editableIf;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Editable constructor.
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
        $resolver->setDefaults(array(
            'default_value' => null,
            'empty_class' => 'editable-empty',
            'empty_text' => 'Empty',
            'highlight' => '#FFFF80',
            'mode' => 'popup',
            'name' => null,
            'pk' => null,
            'type' => 'text',
        ));

        $resolver->setAllowedTypes('default_value', array('string', 'null'));
        $resolver->setAllowedTypes('empty_class', 'string');
        $resolver->setAllowedTypes('empty_text', 'string');
        $resolver->setAllowedTypes('highlight', 'string');
        $resolver->setAllowedTypes('mode', 'string');
        $resolver->setAllowedTypes('name', array('string', 'null'));
        $resolver->setAllowedTypes('pk', array('string', 'null'));
        $resolver->setAllowedTypes('type', 'string');

        $resolver->setAllowedValues('mode', array('popup', 'inline'));
        $resolver->setAllowedValues('type', array('text', 'textarea', 'select', 'date', 'checklist'));

        return $this;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Checks whether the object may be editable.
     *
     * @param array $row
     *
     * @return bool
     */
    public function callEditableIfClosure(array $row = array())
    {
        if ($this->editableIf instanceof Closure) {
            return call_user_func($this->editableIf, $row);
        }

        return true;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get defaultValue.
     *
     * @return null|string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Set defaultValue.
     *
     * @param null|string $defaultValue
     *
     * @return $this
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * Get emptyClass.
     *
     * @return string
     */
    public function getEmptyClass()
    {
        return $this->emptyClass;
    }

    /**
     * Set emptyClass.
     *
     * @param string $emptyClass
     *
     * @return $this
     */
    public function setEmptyClass($emptyClass)
    {
        $this->emptyClass = $emptyClass;

        return $this;
    }

    /**
     * Get emptyText.
     *
     * @return string
     */
    public function getEmptyText()
    {
        return $this->emptyText;
    }

    /**
     * Set emptyText.
     *
     * @param string $emptyText
     *
     * @return $this
     */
    public function setEmptyText($emptyText)
    {
        $this->emptyText = $emptyText;

        return $this;
    }

    /**
     * Get highlight.
     *
     * @return string
     */
    public function getHighlight()
    {
        return $this->highlight;
    }

    /**
     * Set highlight.
     *
     * @param string $highlight
     *
     * @return $this
     */
    public function setHighlight($highlight)
    {
        $this->highlight = $highlight;

        return $this;
    }

    /**
     * Get mode.
     *
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set mode.
     *
     * @param string $mode
     *
     * @return $this
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

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
     * Get pk.
     *
     * @return null|string
     */
    public function getPk()
    {
        return $this->pk;
    }

    /**
     * Set pk.
     *
     * @param null|string $pk
     *
     * @return $this
     */
    public function setPk($pk)
    {
        $this->pk = $pk;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get editableIf.
     *
     * @return null|Closure
     */
    public function getEditableIf()
    {
        return $this->editableIf;
    }

    /**
     * Set editableIf.
     *
     * @param null|Closure $editableIf
     *
     * @return $this
     */
    public function setEditableIf($editableIf)
    {
        $this->editableIf = $editableIf;

        return $this;
    }
}
