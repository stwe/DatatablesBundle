<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Tomáš Polívka <draczris@gmail.com>
 * @author stwe
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class DateTimeColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class DateTimeColumn extends TimeagoColumn
{
    /**
     * DateTime format string.
     *
     * @link http://momentjs.com/
     *
     * @var string
     */
    protected $dateFormat;

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
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Column:datetime.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'datetime';
    }

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'render' => 'render_datetime',
            'date_format' => 'lll',
            'editable' => false,
            'editable_role' => null
        ));

        $resolver->addAllowedTypes('date_format', 'string');
        $resolver->setAllowedTypes('editable', 'bool');
        $resolver->setAllowedTypes('editable_role', array('string', 'null'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set date format.
     *
     * @param string $dateFormat
     *
     * @return $this
     */
    public function setDateFormat($dateFormat)
    {
        if (empty($dateFormat) || !is_string($dateFormat)) {
            throw new InvalidArgumentException('setDateFormat(): Expecting non-empty string.');
        }

        $this->dateFormat = $dateFormat;

        return $this;
    }

    /**
     * Get date format.
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
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
