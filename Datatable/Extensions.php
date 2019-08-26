<?php

/*
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
use Sg\DatatablesBundle\Datatable\Extension\RowGroup;
use Sg\DatatablesBundle\Datatable\Extension\Select;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Extensions
{
    use OptionsTrait;

    //-------------------------------------------------
    // DataTables - Extensions
    //-------------------------------------------------

    /**
     * The Buttons extension.
     * Default: null.
     *
     * @var array|bool|Buttons|null
     */
    protected $buttons;

    /**
     * The Responsive Extension.
     * Automatically optimise the layout for different screen sizes.
     * Default: null.
     *
     * @var array|bool|Responsive|null
     */
    protected $responsive;

    /**
     * The Select Extension.
     * Select adds item selection capabilities to a DataTable.
     * Default: null.
     *
     * @var array|bool|Select|null
     */
    protected $select;

    /**
     * The RowGroup Extension.
     * Automatically group rows.
     * Default: null.
     *
     * @var array|bool|RowGroup|null
     */
    protected $rowGroup;

    public function __construct()
    {
        $this->initOptions();
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
            'buttons' => null,
            'responsive' => null,
            'select' => null,
            'row_group' => null,
        ]);

        $resolver->setAllowedTypes('buttons', ['null', 'array', 'bool']);
        $resolver->setAllowedTypes('responsive', ['null', 'array', 'bool']);
        $resolver->setAllowedTypes('select', ['null', 'array', 'bool']);
        $resolver->setAllowedTypes('row_group', ['null', 'array', 'bool']);

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return array|bool|Buttons|null
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * @param array|bool|null $buttons
     *
     * @return $this
     */
    public function setButtons($buttons)
    {
        if (\is_array($buttons)) {
            $newButton = new Buttons();
            $this->buttons = $newButton->set($buttons);
        } else {
            $this->buttons = $buttons;
        }

        return $this;
    }

    /**
     * @return array|bool|Responsive|null
     */
    public function getResponsive()
    {
        return $this->responsive;
    }

    /**
     * @param array|bool|null $responsive
     *
     * @return $this
     */
    public function setResponsive($responsive)
    {
        if (\is_array($responsive)) {
            $newResponsive = new Responsive();
            $this->responsive = $newResponsive->set($responsive);
        } else {
            $this->responsive = $responsive;
        }

        return $this;
    }

    /**
     * @return array|bool|Select|null
     */
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * @param array|bool|null $select
     *
     * @return $this
     */
    public function setSelect($select)
    {
        if (\is_array($select)) {
            $newSelect = new Select();
            $this->select = $newSelect->set($select);
        } else {
            $this->select = $select;
        }

        return $this;
    }

    /**
     * @return array|bool|RowGroup|null
     */
    public function getRowGroup()
    {
        return $this->rowGroup;
    }

    /**
     * @param array|bool|null $rowGroup
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function setRowGroup($rowGroup)
    {
        if (\is_array($rowGroup)) {
            $newRowGroup = new RowGroup();
            $this->rowGroup = $newRowGroup->set($rowGroup);
        } else {
            $this->rowGroup = $rowGroup;
        }

        return $this;
    }
}
