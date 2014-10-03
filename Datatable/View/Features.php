<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\View;

/**
 * Class Features
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
class Features
{
    /**
     * Feature control DataTables smart column width handling.
     *
     * @var boolean
     */
    private $autoWidth;

    /**
     * Feature control deferred rendering for additional speed of initialisation.
     *
     * @var boolean
     */
    private $deferRender;

    /**
     * Feature control table information display field.
     *
     * @var boolean
     */
    private $info;

    /**
     * Use markup and classes for the table to be themed by jQuery UI ThemeRoller.
     *
     * @var boolean
     * @deprecated in DataTables 1.10 will be removed in 1.11
     */
    private $jQueryUI;

    /**
     * Feature control the end user's ability to change the paging display length of the table.
     *
     * @var boolean
     */
    private $lengthChange;

    /**
     * Feature control ordering (sorting) abilities in DataTables.
     *
     * @var boolean
     */
    private $ordering;

    /**
     * Enable or disable table pagination.
     *
     * @var boolean
     */
    private $paging;

    /**
     * Feature control the processing indicator.
     *
     * @var boolean
     */
    private $processing;

    /**
     * Horizontal scrolling.
     *
     * @var boolean
     */
    private $scrollX;

    /**
     * Vertical scrolling.
     *
     * @var string
     */
    private $scrollY;

    /**
     * Feature control search (filtering) abilities.
     *
     * @var boolean
     */
    private $searching;

    /**
     * Feature control DataTables server-side processing mode.
     *
     * @var boolean
     */
    private $serverSide;

    /**
     * State saving - restore table state on page reload.
     *
     * @var boolean
     */
    private $stateSave;

    /**
     * Delay time to render.
     *
     * @var integer
     */
    private $delay;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->autoWidth = true;
        $this->deferRender = false;
        $this->info = true;
        $this->jQueryUI = false;
        $this->lengthChange = true;
        $this->ordering = true;
        $this->paging = true;
        $this->processing = false;
        $this->scrollX = false;
        $this->scrollY = "";
        $this->searching = true;
        $this->serverSide = false;
        $this->stateSave = false;
        $this->delay = 0;
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set AutoWidth.
     *
     * @param boolean $autoWidth
     *
     * @return $this
     */
    public function setAutoWidth($autoWidth)
    {
        $this->autoWidth = (boolean) $autoWidth;

        return $this;
    }

    /**
     * Get AutoWidth.
     *
     * @return boolean
     */
    public function getAutoWidth()
    {
        return (boolean) $this->autoWidth;
    }

    /**
     * Set DeferRender.
     *
     * @param boolean $deferRender
     *
     * @return $this
     */
    public function setDeferRender($deferRender)
    {
        $this->deferRender = (boolean) $deferRender;

        return $this;
    }

    /**
     * Get DeferRender.
     *
     * @return boolean
     */
    public function getDeferRender()
    {
        return (boolean) $this->deferRender;
    }

    /**
     * Set Info.
     *
     * @param boolean $info
     *
     * @return $this
     */
    public function setInfo($info)
    {
        $this->info = (boolean) $info;

        return $this;
    }

    /**
     * Get Info.
     *
     * @return boolean
     */
    public function getInfo()
    {
        return (boolean) $this->info;
    }

    /**
     * Set JQueryUI.
     *
     * @param boolean $jQueryUI
     *
     * @return $this
     */
    public function setJQueryUI($jQueryUI)
    {
        $this->jQueryUI = (boolean) $jQueryUI;

        return $this;
    }

    /**
     * Get JQueryUI.
     *
     * @return boolean
     */
    public function getJQueryUI()
    {
        return (boolean) $this->jQueryUI;
    }

    /**
     * Set LengthChange.
     *
     * @param boolean $lengthChange
     *
     * @return $this
     */
    public function setLengthChange($lengthChange)
    {
        $this->lengthChange = (boolean) $lengthChange;

        return $this;
    }

    /**
     * Get LengthChange.
     *
     * @return boolean
     */
    public function getLengthChange()
    {
        return (boolean) $this->lengthChange;
    }

    /**
     * Set Ordering.
     *
     * @param boolean $ordering
     *
     * @return $this
     */
    public function setOrdering($ordering)
    {
        $this->ordering = (boolean) $ordering;

        return $this;
    }

    /**
     * Get Ordering.
     *
     * @return boolean
     */
    public function getOrdering()
    {
        return (boolean) $this->ordering;
    }

    /**
     * Set Paging.
     *
     * @param boolean $paging
     *
     * @return $this
     */
    public function setPaging($paging)
    {
        $this->paging = (boolean) $paging;

        return $this;
    }

    /**
     * Get Paging.
     *
     * @return boolean
     */
    public function getPaging()
    {
        return (boolean) $this->paging;
    }

    /**
     * Set Processing.
     *
     * @param boolean $processing
     *
     * @return $this
     */
    public function setProcessing($processing)
    {
        $this->processing = (boolean) $processing;

        return $this;
    }

    /**
     * Get Processing.
     *
     * @return boolean
     */
    public function getProcessing()
    {
        return (boolean) $this->processing;
    }

    /**
     * Set ScrollX.
     *
     * @param boolean $scrollX
     *
     * @return $this
     */
    public function setScrollX($scrollX)
    {
        $this->scrollX = (boolean) $scrollX;

        return $this;
    }

    /**
     * Get ScrollX.
     *
     * @return boolean
     */
    public function getScrollX()
    {
        return (boolean) $this->scrollX;
    }

    /**
     * Set ScrollY.
     *
     * @param string $scrollY
     *
     * @return $this
     */
    public function setScrollY($scrollY)
    {
        $this->scrollY = $scrollY;

        return $this;
    }

    /**
     * Get ScrollY
     *
     * @return string
     */
    public function getScrollY()
    {
        return $this->scrollY;
    }

    /**
     * Set Searching.
     *
     * @param boolean $searching
     *
     * @return $this
     */
    public function setSearching($searching)
    {
        $this->searching = (boolean) $searching;

        return $this;
    }

    /**
     * Get Searching.
     *
     * @return boolean
     */
    public function getSearching()
    {
        return (boolean) $this->searching;
    }

    /**
     * Set ServerSide.
     *
     * @param boolean $serverSide
     *
     * @return $this
     */
    public function setServerSide($serverSide)
    {
        $this->serverSide = (boolean) $serverSide;

        return $this;
    }

    /**
     * Get ServerSide.
     *
     * @return boolean
     */
    public function getServerSide()
    {
        return (boolean) $this->serverSide;
    }

    /**
     * Set StateSave.
     *
     * @param boolean $stateSave
     *
     * @return $this
     */
    public function setStateSave($stateSave)
    {
        $this->stateSave = (boolean) $stateSave;

        return $this;
    }

    /**
     * Get StateSave.
     *
     * @return boolean
     */
    public function getStateSave()
    {
        return (boolean) $this->stateSave;
    }

    /**
     * Set delay.
     *
     * @param integer $delay
     *
     * @return $this
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;

        return $this;
    }

    /**
     * Get delay.
     *
     * @return integer
     */
    public function getDelay()
    {
        return $this->delay;
    }
}
