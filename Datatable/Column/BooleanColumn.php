<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class BooleanColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class BooleanColumn extends AbstractColumn
{
    /**
     * The icon for a value that is true.
     *
     * @var string
     */
    protected $trueIcon;

    /**
     * The icon for a value that is false.
     *
     * @var string
     */
    protected $falseIcon;

    /**
     * The label for a value that is true.
     *
     * @var string
     */
    protected $trueLabel;

    /**
     * The label for a value that is false.
     *
     * @var string
     */
    protected $falseLabel;

    /**
     * Editable flag.
     *
     * @var boolean
     */
    protected $editable;

    /**
     * Role based editing permission.
     *
     * @var null|string
     */
    protected $editableRole;

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        if (empty($data) || !is_string($data)) {
            throw new InvalidArgumentException('setData(): Expecting non-empty string.');
        }

        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Column:boolean.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'boolean';
    }

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' => '',
            'padding' => '',
            'name' => '',
            'orderable' => true,
            'render' => 'render_boolean',
            'searchable' => true,
            'title' => '',
            'type' => '',
            'visible' => true,
            'width' => '',
            'filter' => array('select', array(
                'search_type' => 'eq',
                'select_options' => array('' => 'Any', '1' => 'Yes', '0' => 'No')
            )),
            'true_icon' => '',
            'false_icon' => '',
            'true_label' => '',
            'false_label' => '',
            'editable' => false,
            'editable_role' => null
        ));

        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedTypes('padding', 'string');
        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('orderable', 'bool');
        $resolver->setAllowedTypes('render', 'string');
        $resolver->setAllowedTypes('searchable', 'bool');
        $resolver->setAllowedTypes('title', 'string');
        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedTypes('visible', 'bool');
        $resolver->setAllowedTypes('width', 'string');
        $resolver->setAllowedTypes('filter', 'array');
        $resolver->setAllowedTypes('true_icon', 'string');
        $resolver->setAllowedTypes('false_icon', 'string');
        $resolver->setAllowedTypes('true_label', 'string');
        $resolver->setAllowedTypes('false_label', 'string');
        $resolver->setAllowedTypes('editable', 'bool');
        $resolver->setAllowedTypes('editable_role', array('string', 'null'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set false icon.
     *
     * @param string $falseIcon
     *
     * @return $this
     */
    public function setFalseIcon($falseIcon)
    {
        $this->falseIcon = $falseIcon;

        return $this;
    }

    /**
     * Get false icon.
     *
     * @return string
     */
    public function getFalseIcon()
    {
        return $this->falseIcon;
    }

    /**
     * Set true icon.
     *
     * @param string $trueIcon
     *
     * @return $this
     */
    public function setTrueIcon($trueIcon)
    {
        $this->trueIcon = $trueIcon;

        return $this;
    }

    /**
     * Get true icon.
     *
     * @return string
     */
    public function getTrueIcon()
    {
        return $this->trueIcon;
    }

    /**
     * Set false label.
     *
     * @param string $falseLabel
     *
     * @return $this
     */
    public function setFalseLabel($falseLabel)
    {
        $this->falseLabel = $falseLabel;

        return $this;
    }

    /**
     * Get false label.
     *
     * @return string
     */
    public function getFalseLabel()
    {
        return $this->falseLabel;
    }

    /**
     * Set true label.
     *
     * @param string $trueLabel
     *
     * @return $this
     */
    public function setTrueLabel($trueLabel)
    {
        $this->trueLabel = $trueLabel;

        return $this;
    }

    /**
     * Get false label.
     *
     * @return string
     */
    public function getTrueLabel()
    {
        return $this->trueLabel;
    }

    /**
     * Set editable.
     *
     * @param boolean $editable
     *
     * @return $this
     */
    public function setEditable($editable)
    {
        $this->editable = $editable;

        return $this;
    }

    /**
     * Get editable.
     *
     * @return boolean
     */
    public function getEditable()
    {
        return $this->editable;
    }

    /**
     * Set editable role.
     *
     * @param null|string $editableRole
     *
     * @return $this
     */
    public function setEditableRole($editableRole)
    {
        $this->editableRole = $editableRole;

        return $this;
    }

    /**
     * Get editable role.
     *
     * @return null|string
     */
    public function getEditableRole()
    {
        return $this->editableRole;
    }
}
