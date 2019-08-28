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

use Symfony\Component\OptionsResolver\OptionsResolver;

class Features
{
    use OptionsTrait;

    //--------------------------------------------------------------------------------------------------
    // DataTables - Features
    // ---------------------
    // All DataTables Features are initialized with 'null'.
    // These 'null' initialized Features uses the default value of the DataTables plugin.
    //--------------------------------------------------------------------------------------------------

    /**
     * Feature control DataTables' smart column width handling.
     * DataTables default: true
     * Default: null.
     *
     * @var bool|null
     */
    protected $autoWidth;

    /**
     * Feature control deferred rendering for additional speed of initialisation.
     * DataTables default: false
     * Default: null.
     *
     * @var bool|null
     */
    protected $deferRender;

    /**
     * Feature control table information display field.
     * DataTables default: true
     * Default: null.
     *
     * @var bool|null
     */
    protected $info;

    /**
     * Feature control the end user's ability to change the paging display length of the table.
     * DataTables default: true
     * Default: null.
     *
     * @var bool|null
     */
    protected $lengthChange;

    /**
     * Feature control ordering (sorting) abilities in DataTables.
     * DataTables default: true
     * Default: null.
     *
     * @var bool|null
     */
    protected $ordering;

    /**
     * Enable or disable table pagination.
     * DataTables default: true
     * Default: null.
     *
     * @var bool|null
     */
    protected $paging;

    /**
     * Feature control the processing indicator.
     * DataTables default: false
     * Default: null.
     *
     * @var bool|null
     */
    protected $processing;

    /**
     * Horizontal scrolling.
     * DataTables default: false
     * Default: null.
     *
     * @var bool|null
     */
    protected $scrollX;

    /**
     * Vertical scrolling.
     * Default: null.
     *
     * @var string|null
     */
    protected $scrollY;

    /**
     * Feature control search (filtering) abilities.
     * DataTables default: true
     * Default: null.
     *
     * @var bool|null
     */
    protected $searching;

    /**
     * State saving - restore table state on page reload.
     * DataTables default: false
     * Default: null.
     *
     * @var bool|null
     */
    protected $stateSave;

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
            'auto_width' => null,
            'defer_render' => null,
            'info' => null,
            'length_change' => null,
            'ordering' => null,
            'paging' => null,
            'processing' => null,
            'scroll_x' => null,
            'scroll_y' => null,
            'searching' => null,
            'state_save' => null,
        ]);

        $resolver->setAllowedTypes('auto_width', ['null', 'bool']);
        $resolver->setAllowedTypes('defer_render', ['null', 'bool']);
        $resolver->setAllowedTypes('info', ['null', 'bool']);
        $resolver->setAllowedTypes('length_change', ['null', 'bool']);
        $resolver->setAllowedTypes('ordering', ['null', 'bool']);
        $resolver->setAllowedTypes('paging', ['null', 'bool']);
        $resolver->setAllowedTypes('processing', ['null', 'bool']);
        $resolver->setAllowedTypes('scroll_x', ['null', 'bool']);
        $resolver->setAllowedTypes('scroll_y', ['null', 'string']);
        $resolver->setAllowedTypes('searching', ['null', 'bool']);
        $resolver->setAllowedTypes('state_save', ['null', 'bool']);

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return bool|null
     */
    public function getAutoWidth()
    {
        return $this->autoWidth;
    }

    /**
     * @param bool|null $autoWidth
     *
     * @return $this
     */
    public function setAutoWidth($autoWidth)
    {
        $this->autoWidth = $autoWidth;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getDeferRender()
    {
        return $this->deferRender;
    }

    /**
     * @param bool|null $deferRender
     *
     * @return $this
     */
    public function setDeferRender($deferRender)
    {
        $this->deferRender = $deferRender;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param bool|null $info
     *
     * @return $this
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getLengthChange()
    {
        return $this->lengthChange;
    }

    /**
     * @param bool|null $lengthChange
     *
     * @return $this
     */
    public function setLengthChange($lengthChange)
    {
        $this->lengthChange = $lengthChange;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * @param bool|null $ordering
     *
     * @return $this
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getPaging()
    {
        return $this->paging;
    }

    /**
     * @param bool|null $paging
     *
     * @return $this
     */
    public function setPaging($paging)
    {
        $this->paging = $paging;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getProcessing()
    {
        return $this->processing;
    }

    /**
     * @param bool|null $processing
     *
     * @return $this
     */
    public function setProcessing($processing)
    {
        $this->processing = $processing;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getScrollX()
    {
        return $this->scrollX;
    }

    /**
     * @param bool|null $scrollX
     *
     * @return $this
     */
    public function setScrollX($scrollX)
    {
        $this->scrollX = $scrollX;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getScrollY()
    {
        return $this->scrollY;
    }

    /**
     * @param string|null $scrollY
     *
     * @return $this
     */
    public function setScrollY($scrollY)
    {
        $this->scrollY = $scrollY;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getSearching()
    {
        return $this->searching;
    }

    /**
     * @param bool|null $searching
     *
     * @return $this
     */
    public function setSearching($searching)
    {
        $this->searching = $searching;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getStateSave()
    {
        return $this->stateSave;
    }

    /**
     * @param bool|null $stateSave
     *
     * @return $this
     */
    public function setStateSave($stateSave)
    {
        $this->stateSave = $stateSave;

        return $this;
    }
}
