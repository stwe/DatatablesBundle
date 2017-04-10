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

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Features
 *
 * @package Sg\DatatablesBundle\Datatable
 */
class Features
{
    /**
     * Use the OptionsResolver.
     */
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
     * Default: null
     *
     * @var null|bool
     */
    protected $autoWidth;

    /**
     * Feature control deferred rendering for additional speed of initialisation.
     * DataTables default: false
     * Default: null
     *
     * @var null|bool
     */
    protected $deferRender;

    /**
     * Feature control table information display field.
     * DataTables default: true
     * Default: null
     *
     * @var null|bool
     */
    protected $info;

    /**
     * Feature control the end user's ability to change the paging display length of the table.
     * DataTables default: true
     * Default: null
     *
     * @var null|bool
     */
    protected $lengthChange;

    /**
     * Feature control ordering (sorting) abilities in DataTables.
     * DataTables default: true
     * Default: null
     *
     * @var null|bool
     */
    protected $ordering;

    /**
     * Enable or disable table pagination.
     * DataTables default: true
     * Default: null
     *
     * @var null|bool
     */
    protected $paging;

    /**
     * Feature control the processing indicator.
     * DataTables default: false
     * Default: null
     *
     * @var null|bool
     */
    protected $processing;

    /**
     * Horizontal scrolling.
     * DataTables default: false
     * Default: null
     *
     * @var null|bool
     */
    protected $scrollX;

    /**
     * Vertical scrolling.
     * Default: null
     *
     * @var null|string
     */
    protected $scrollY;

    /**
     * Feature control search (filtering) abilities.
     * DataTables default: true
     * Default: null
     *
     * @var null|bool
     */
    protected $searching;

    /**
     * State saving - restore table state on page reload.
     * DataTables default: false
     * Default: null
     *
     * @var null|bool
     */
    protected $stateSave;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Features constructor.
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
        ));

        $resolver->setAllowedTypes('auto_width', array('null', 'bool'));
        $resolver->setAllowedTypes('defer_render', array('null', 'bool'));
        $resolver->setAllowedTypes('info', array('null', 'bool'));
        $resolver->setAllowedTypes('length_change', array('null', 'bool'));
        $resolver->setAllowedTypes('ordering', array('null', 'bool'));
        $resolver->setAllowedTypes('paging', array('null', 'bool'));
        $resolver->setAllowedTypes('processing', array('null', 'bool'));
        $resolver->setAllowedTypes('scroll_x', array('null', 'bool'));
        $resolver->setAllowedTypes('scroll_y', array('null', 'string'));
        $resolver->setAllowedTypes('searching', array('null', 'bool'));
        $resolver->setAllowedTypes('state_save', array('null', 'bool'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get autoWidth.
     *
     * @return bool|null
     */
    public function getAutoWidth()
    {
        return $this->autoWidth;
    }

    /**
     * Set autoWidth.
     *
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
     * Get deferRender.
     *
     * @return bool|null
     */
    public function getDeferRender()
    {
        return $this->deferRender;
    }

    /**
     * Set deferRender.
     *
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
     * Get info.
     *
     * @return bool|null
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set info.
     *
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
     * Get lengthChange.
     *
     * @return bool|null
     */
    public function getLengthChange()
    {
        return $this->lengthChange;
    }

    /**
     * Set lengthChange.
     *
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
     * Get ordering.
     *
     * @return bool|null
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set ordering.
     *
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
     * Get paging.
     *
     * @return bool|null
     */
    public function getPaging()
    {
        return $this->paging;
    }

    /**
     * Set paging.
     *
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
     * Get processing.
     *
     * @return bool|null
     */
    public function getProcessing()
    {
        return $this->processing;
    }

    /**
     * Set processing.
     *
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
     * Get scrollX.
     *
     * @return bool|null
     */
    public function getScrollX()
    {
        return $this->scrollX;
    }

    /**
     * Set scrollX.
     *
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
     * Get scrollY.
     *
     * @return null|string
     */
    public function getScrollY()
    {
        return $this->scrollY;
    }

    /**
     * Set scrollY.
     *
     * @param null|string $scrollY
     *
     * @return $this
     */
    public function setScrollY($scrollY)
    {
        $this->scrollY = $scrollY;

        return $this;
    }

    /**
     * Get searching.
     *
     * @return bool|null
     */
    public function getSearching()
    {
        return $this->searching;
    }

    /**
     * Set searching.
     *
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
     * Get stateSave.
     *
     * @return bool|null
     */
    public function getStateSave()
    {
        return $this->stateSave;
    }

    /**
     * Set stateSave.
     *
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
