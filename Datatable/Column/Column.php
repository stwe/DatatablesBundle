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
 * Class Column
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class Column extends AbstractColumn
{
    /**
     * Default content.
     *
     * @var string
     */
    protected $default;

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
        return 'SgDatatablesBundle:Column:column.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'column';
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
            'render' => null,
            'searchable' => true,
            'title' => '',
            'type' => '',
            'visible' => true,
            'width' => '',
            'filter' => array('text', array()),
            'default' => '',
            'editable' => false,
            'editable_role' => null
        ));

        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedTypes('padding', 'string');
        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('orderable', 'bool');
        $resolver->setAllowedTypes('render', array('string', 'null'));
        $resolver->setAllowedTypes('searchable', 'bool');
        $resolver->setAllowedTypes('title', 'string');
        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedTypes('visible', 'bool');
        $resolver->setAllowedTypes('width', 'string');
        $resolver->setAllowedTypes('filter', 'array');
        $resolver->setAllowedTypes('default', 'string');
        $resolver->setAllowedTypes('editable', 'bool');
        $resolver->setAllowedTypes('editable_role', array('string', 'null'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get default.
     *
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Set default.
     *
     * @param string $default
     *
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
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
