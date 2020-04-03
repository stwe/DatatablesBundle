<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Editable;

use Closure;
use Sg\DatatablesBundle\Datatable\OptionsTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractEditable implements EditableInterface
{
    use OptionsTrait;

    //-------------------------------------------------
    // X-editable Options
    //-------------------------------------------------

    /**
     * Url for submit.
     * Default: 'sg_datatables_edit'.
     *
     * @var string
     */
    protected $url;

    /**
     * Additional params for submit It is appended to original ajax data (pk, name and value).
     * Default: null.
     *
     * @var array|null
     */
    protected $params;

    /**
     * Value that will be displayed in input if original field value is empty (null|undefined|'').
     * Default: null.
     *
     * @var string|null
     */
    protected $defaultValue;

    /**
     * Css class applied when editable text is empty.
     * Default: 'editable-empty'.
     *
     * @var string
     */
    protected $emptyClass;

    /**
     * Text shown when element is empty.
     * Default: 'Empty'.
     *
     * @var string
     */
    protected $emptyText;

    /**
     * Color used to highlight element after update.
     * Default: '#FFFF80'.
     *
     * @var string
     */
    protected $highlight;

    /**
     * Mode of editable, can be 'popup' or 'inline'.
     * Default: 'popup'.
     *
     * @var string
     */
    protected $mode;

    /**
     * Name of field. Will be submitted on server. Can be taken from id attribute.
     * Default: null.
     *
     * @var string|null
     */
    protected $name;

    /**
     * Primary key of editable object.
     * Default: 'id'.
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
     * @var Closure|null
     */
    protected $editableIf;

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
    public function callEditableIfClosure(array $row = [])
    {
        if ($this->editableIf instanceof Closure) {
            return \call_user_func($this->editableIf, $row);
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
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
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
        ]);

        $resolver->setAllowedTypes('url', 'string');
        $resolver->setAllowedTypes('params', ['null', 'array']);
        $resolver->setAllowedTypes('default_value', ['string', 'null']);
        $resolver->setAllowedTypes('empty_class', 'string');
        $resolver->setAllowedTypes('empty_text', 'string');
        $resolver->setAllowedTypes('highlight', 'string');
        $resolver->setAllowedTypes('mode', 'string');
        $resolver->setAllowedTypes('name', ['string', 'null']);
        $resolver->setAllowedTypes('pk', 'string');
        $resolver->setAllowedTypes('editable_if', ['Closure', 'null']);

        $resolver->setAllowedValues('mode', ['popup', 'inline']);

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
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
     * @return array|null
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array|null $params
     *
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param string|null $defaultValue
     *
     * @return $this
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmptyClass()
    {
        return $this->emptyClass;
    }

    /**
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
     * @return string
     */
    public function getHighlight()
    {
        return $this->highlight;
    }

    /**
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
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
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
     * @return Closure|null
     */
    public function getEditableIf()
    {
        return $this->editableIf;
    }

    /**
     * @param Closure|null $editableIf
     *
     * @return $this
     */
    public function setEditableIf($editableIf)
    {
        $this->editableIf = $editableIf;

        return $this;
    }
}
