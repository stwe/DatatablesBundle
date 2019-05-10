<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable;

use Sg\DatatablesBundle\Datatable\Extension\Buttons;
use Sg\DatatablesBundle\Datatable\Extension\Responsive;
use Sg\DatatablesBundle\Datatable\Extension\Select;
use Sg\DatatablesBundle\Datatable\Extension\RowGroup;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Extensions
 *
 * @package Sg\DatatablesBundle\Datatable
 */
class Extensions
{
    /**
     * Use the OptionsResolver.
     */
    use OptionsTrait;

    //-------------------------------------------------
    // DataTables - Extensions
    //-------------------------------------------------

    /**
     * The Buttons extension.
     * Default: null
     *
     * @var null|array|bool|Buttons
     */
    protected $buttons;

    /**
     * The Responsive Extension.
     * Automatically optimise the layout for different screen sizes.
     * Default: null
     *
     * @var null|array|bool|Responsive
     */
    protected $responsive;

    /**
     * The Select Extension.
     * Select adds item selection capabilities to a DataTable.
     * Default: null
     *
     * @var null|array|bool|Select
     */
    protected $select;

    /**
     * The RowGroup Extension.
     * Automatically group rows.
     * Default: null
     *
     * @var null|array|bool|RowGroup
     */
    protected $rowGroup;

    /**
     * The ColReorder Extension.
     * Reorder columns
     * Default: null
     *
     * @var null|bool
     */
    protected $colReorder;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Extensions constructor.
     */
    public function __construct()
    {
        $this->initOptions();
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
            'buttons' => null,
            'responsive' => null,
            'select' => null,
            'row_group' => null,
            'col_reorder' => null,
        ));

        $resolver->setAllowedTypes('buttons', array('null', 'array', 'bool'));
        $resolver->setAllowedTypes('responsive', array('null', 'array', 'bool'));
        $resolver->setAllowedTypes('select', array('null', 'array', 'bool'));
        $resolver->setAllowedTypes('row_group', array('null', 'array', 'bool'));
        $resolver->setAllowedTypes('col_reorder', array('null', 'bool'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get buttons.
     *
     * @return null|array|bool|Buttons
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * Set buttons.
     *
     * @param null|array|bool $buttons
     *
     * @return $this
     */
    public function setButtons($buttons)
    {
        if (is_array($buttons)) {
            $newButton = new Buttons();
            $this->buttons = $newButton->set($buttons);
        } else {
            $this->buttons = $buttons;
        }

        return $this;
    }

    /**
     * Get responsive.
     *
     * @return null|array|bool|Responsive
     */
    public function getResponsive()
    {
        return $this->responsive;
    }

    /**
     * Set responsive.
     *
     * @param null|array|bool $responsive
     *
     * @return $this
     */
    public function setResponsive($responsive)
    {
        if (is_array($responsive)) {
            $newResponsive = new Responsive();
            $this->responsive = $newResponsive->set($responsive);
        } else {
            $this->responsive = $responsive;
        }

        return $this;
    }

    /**
     * Get select.
     *
     * @return null|array|bool|Select
     */
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * Set select.
     *
     * @param null|array|bool $select
     *
     * @return $this
     */
    public function setSelect($select)
    {
        if (is_array($select)) {
            $newSelect = new Select();
            $this->select = $newSelect->set($select);
        } else {
            $this->select = $select;
        }

        return $this;
    }

    /**
     * Get rowGroup.
     *
     * @return null|array|bool|RowGroup
     */
    public function getRowGroup()
    {
        return $this->rowGroup;
    }

    /**
     * Set rowGroup.
     *
     * @param null|array|bool $rowGroup
     * @return $this
     * @throws \Exception
     */
    public function setRowGroup($rowGroup)
    {
        if (is_array($rowGroup)) {
            $newRowGroup = new RowGroup();
            $this->rowGroup = $newRowGroup->set($rowGroup);
        } else {
            $this->rowGroup = $rowGroup;
        }

        return $this;
    }

    /**
     * Get colReorder.
     *
     * @return null|bool
     */
    public function getColReorder()
    {
        return $this->colReorder;
    }

    /**
     * Set colReorder.
     *
     * @param null|bool
     * @return $this
     */
    public function setColReorder($colReorder)
    {
        $this->colReorder = $colReorder;

        return $this;
    }
}
