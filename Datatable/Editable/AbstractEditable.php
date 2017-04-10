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
 * Class AbstractEditable
 *
 * @package Sg\DatatablesBundle\Datatable\Editable
 */
abstract class AbstractEditable implements EditableInterface
{
    /**
     * Use the OptionsResolver.
     */
    use OptionsTrait;

    //-------------------------------------------------
    // X-editable Options
    //-------------------------------------------------

    /**
     * Url for submit.
     * Default: 'sg_datatables_edit'
     *
     * @var string
     */
    protected $url;

    /**
     * Additional params for submit It is appended to original ajax data (pk, name and value).
     * Default: null
     *
     * @var null|array
     */
    protected $params;

    /**
     * Value that will be displayed in input if original field value is empty (null|undefined|'').
     * Default: null
     *
     * @var null|string
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
     * @var null|string
     */
    protected $name;

    /**
     * Primary key of editable object.
     * Default: 'id'
     *
     * @var string
     */
    protected $pk;

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
     * AbstractEditable constructor.
     */
    public function __construct()
    {
        $this->initOptions();
    }

    //-------------------------------------------------
    // EditableInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function callEditableIfClosure(array $row = array())
    {
        if ($this->editableIf instanceof Closure) {
            return call_user_func($this->editableIf, $row);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getPk()
    {
        return $this->pk;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmptyText()
    {
        return $this->emptyText;
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
            'url' => 'sg_datatables_edit',
            'params' => null,
            'default_value' => null,
            'empty_class' => 'editable-empty',
            'empty_text' => 'Empty',
            'highlight' => '#FFFF80',
            'mode' => 'popup',
            'name' => null,
            'pk' => 'id',
            'editable_if' => null,
        ));

        $resolver->setAllowedTypes('url', 'string');
        $resolver->setAllowedTypes('params', array('null', 'array'));
        $resolver->setAllowedTypes('default_value', array('string', 'null'));
        $resolver->setAllowedTypes('empty_class', 'string');
        $resolver->setAllowedTypes('empty_text', 'string');
        $resolver->setAllowedTypes('highlight', 'string');
        $resolver->setAllowedTypes('mode', 'string');
        $resolver->setAllowedTypes('name', array('string', 'null'));
        $resolver->setAllowedTypes('pk', 'string');
        $resolver->setAllowedTypes('editable_if', array('Closure', 'null'));

        $resolver->setAllowedValues('mode', array('popup', 'inline'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get params.
     *
     * @return null|array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set params.
     *
     * @param null|array $params
     *
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

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
     * Set pk.
     *
     * @param string $pk
     *
     * @return $this
     */
    public function setPk($pk)
    {
        $this->pk = $pk;

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
