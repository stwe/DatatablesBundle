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
 * Class Options
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
class Options
{
    //-------------------------------------------------
    // Built-in paging button arrangements
    //-------------------------------------------------

    /**
     * "Previous" and "Next" buttons only.
     *
     * @var string
     */
    const SIMPLE_PAGINATION = "simple";

    /**
     * "Previous" and "Next" buttons, plus page numbers.
     *
     * @var string
     */
    const SIMPLE_NUMBERS_PAGINATION = "simple_numbers";

    /**
     * "First", "Previous", "Next" and "Last" buttons.
     *
     * @var string
     */
    const FULL_PAGINATION = "full";

    /**
     * "First", "Previous", "Next" and "Last" buttons, plus page numbers.
     *
     * @var string
     */
    const FULL_NUMBERS_PAGINATION = "full_numbers";


    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * Delay the loading of server-side data until second draw.
     *
     * @var integer|array
     */
    private $deferLoading;

    /**
     * Destroy any existing table matching the selector and replace with the new options.
     *
     * @var boolean
     */
    private $destroy;

    /**
     * Initial paging start point.
     *
     * @var integer
     */
    private $displayStart;

    /**
     * Define the table control elements to appear on the page and in what order.
     *
     * @var string
     */
    private $dom;

    /**
     * Change the options in the page length select list.
     *
     * @var array
     */
    private $lengthMenu;

    /**
     * Control which cell the order event handler will be applied to in a column.
     *
     * @var boolean
     */
    private $orderCellsTop;

    /**
     * Highlight the columns being ordered in the table's body.
     *
     * @var boolean
     */
    private $orderClasses;

    /**
     * Initial order (sort) to apply to the table.
     *
     * @var array
     */
    private $order;

    /**
     * Ordering to always be applied to the table.
     *
     * @var array|object
     */
    private $orderFixed;

    /**
     * Multiple column ordering ability control.
     *
     * @var boolean
     */
    private $orderMulti;

    /**
     * Change the initial page length (number of rows per page).
     *
     * @var integer
     */
    private $pageLength;

    /**
     * Pagination button display options.
     *
     * @var string
     */
    private $pagingType;

    /**
     * Display component renderer types.
     *
     * @var string|object
     */
    private $renderer;

    /**
     * Retrieve an existing DataTables instance.
     *
     * @var boolean
     */
    private $retrieve;

    /**
     * Allow the table to reduce in height when a limited number of rows are shown.
     *
     * @var boolean
     */
    private $scrollCollapse;

    // search.caseInsensitive
    // search.regex
    // search.search
    // search.smart

    /**
     * Define an initial search for individual columns.
     *
     * @var array
     */
    private $searchCols;

    /**
     * Set an initial filter in DataTables and / or filtering options.
     *
     * @var object
     */
    private $search;

    /**
     * Saved state validity duration.
     *
     * @var integer
     */
    private $stateDuration;

    /**
     * Set the zebra stripe class names for the rows in the table.
     *
     * @var array
     */
    private $stripeClasses;

    /**
     * Tab index control for keyboard navigation.
     *
     * @var integer
     */
    private $tabIndex;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->destroy = false;
        $this->displayStart = 0;
        $this->dom = "lfrtip";
        $this->lengthMenu = array(10, 25, 50, 100);
        $this->orderCellsTop = false;
        $this->orderClasses = true;
        $this->order = array(0, "asc");
        $this->orderMulti = true;
        $this->pageLength = 10;
        $this->pagingType = self::SIMPLE_NUMBERS_PAGINATION;
        $this->retrieve = false;
        $this->scrollCollapse = false;
        $this->stateDuration = 7200;
        $this->tabIndex = 0;
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set DeferLoading.
     *
     * @param array|int $deferLoading
     *
     * @return $this
     */
    public function setDeferLoading($deferLoading)
    {
        $this->deferLoading = $deferLoading;

        return $this;
    }

    /**
     * Get DeferLoading.
     *
     * @return array|int
     */
    public function getDeferLoading()
    {
        return $this->deferLoading;
    }

    /**
     * Set Destroy.
     *
     * @param boolean $destroy
     *
     * @return $this
     */
    public function setDestroy($destroy)
    {
        $this->destroy = (boolean) $destroy;

        return $this;
    }

    /**
     * Get Destroy.
     *
     * @return boolean
     */
    public function getDestroy()
    {
        return (boolean) $this->destroy;
    }

    /**
     * Set DisplayStart.
     *
     * @param int $displayStart
     *
     * @return $this
     */
    public function setDisplayStart($displayStart)
    {
        $this->displayStart = (integer) $displayStart;

        return $this;
    }

    /**
     * Get DisplayStart.
     *
     * @return int
     */
    public function getDisplayStart()
    {
        return (integer) $this->displayStart;
    }

    /**
     * Set Dom.
     *
     * @param string $dom
     *
     * @return $this
     */
    public function setDom($dom)
    {
        $this->dom = $dom;

        return $this;
    }

    /**
     * Get Dom.
     *
     * @return string
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * Set LengthMenu.
     *
     * @param array $lengthMenu
     *
     * @return $this
     */
    public function setLengthMenu($lengthMenu)
    {
        $this->lengthMenu = $lengthMenu;

        return $this;
    }

    /**
     * Get LengthMenu.
     *
     * @return array
     */
    public function getLengthMenu()
    {
        return $this->lengthMenu;
    }

    /**
     * Set Order.
     *
     * @param array $order
     *
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get Order.
     *
     * @return array
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set OrderCellsTop.
     *
     * @param boolean $orderCellsTop
     *
     * @return $this
     */
    public function setOrderCellsTop($orderCellsTop)
    {
        $this->orderCellsTop = (boolean) $orderCellsTop;

        return $this;
    }

    /**
     * Get OrderCellsTop.
     *
     * @return boolean
     */
    public function getOrderCellsTop()
    {
        return (boolean) $this->orderCellsTop;
    }

    /**
     * Set OrderClasses.
     *
     * @param boolean $orderClasses
     *
     * @return $this
     */
    public function setOrderClasses($orderClasses)
    {
        $this->orderClasses = (boolean) $orderClasses;

        return $this;
    }

    /**
     * Get OrderClasses.
     *
     * @return boolean
     */
    public function getOrderClasses()
    {
        return (boolean) $this->orderClasses;
    }

    /**
     * Set OrderFixed.
     *
     * @param array|object $orderFixed
     *
     * @return $this
     */
    public function setOrderFixed($orderFixed)
    {
        $this->orderFixed = $orderFixed;

        return $this;
    }

    /**
     * Get OrderFixed.
     *
     * @return array|object
     */
    public function getOrderFixed()
    {
        return $this->orderFixed;
    }

    /**
     * Set OrderMulti.
     *
     * @param boolean $orderMulti
     *
     * @return $this
     */
    public function setOrderMulti($orderMulti)
    {
        $this->orderMulti = (boolean) $orderMulti;

        return $this;
    }

    /**
     * Get OrderMulti.
     *
     * @return boolean
     */
    public function getOrderMulti()
    {
        return (boolean) $this->orderMulti;
    }

    /**
     * Set PageLength.
     *
     * @param int $pageLength
     *
     * @return $this
     */
    public function setPageLength($pageLength)
    {
        $this->pageLength = (integer) $pageLength;

        return $this;
    }

    /**
     * Get PageLength.
     *
     * @return int
     */
    public function getPageLength()
    {
        return (integer) $this->pageLength;
    }

    /**
     * Set PagingType.
     *
     * @param string $pagingType
     *
     * @return $this
     */
    public function setPagingType($pagingType)
    {
        $this->pagingType = $pagingType;

        return $this;
    }

    /**
     * Get PagingType.
     *
     * @return string
     */
    public function getPagingType()
    {
        return $this->pagingType;
    }

    /**
     * Set Renderer.
     *
     * @param object|string $renderer
     *
     * @return $this
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * Get Renderer.
     *
     * @return object|string
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * Set Retrieve.
     *
     * @param boolean $retrieve
     *
     * @return $this
     */
    public function setRetrieve($retrieve)
    {
        $this->retrieve = (boolean) $retrieve;

        return $this;
    }

    /**
     * Get Retrieve.
     *
     * @return boolean
     */
    public function getRetrieve()
    {
        return (boolean) $this->retrieve;
    }

    /**
     * Set ScrollCollapse.
     *
     * @param boolean $scrollCollapse
     *
     * @return $this
     */
    public function setScrollCollapse($scrollCollapse)
    {
        $this->scrollCollapse = (boolean) $scrollCollapse;

        return $this;
    }

    /**
     * Get ScrollCollapse.
     *
     * @return boolean
     */
    public function getScrollCollapse()
    {
        return (boolean) $this->scrollCollapse;
    }

    /**
     * Set Search.
     *
     * @param object $search
     *
     * @return $this
     */
    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * Get Search.
     *
     * @return object
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Set SearchCols.
     *
     * @param array $searchCols
     *
     * @return $this
     */
    public function setSearchCols($searchCols)
    {
        $this->searchCols = $searchCols;

        return $this;
    }

    /**
     * Get SearchCols.
     *
     * @return array
     */
    public function getSearchCols()
    {
        return $this->searchCols;
    }

    /**
     * Set StateDuration.
     *
     * @param int $stateDuration
     *
     * @return $this
     */
    public function setStateDuration($stateDuration)
    {
        $this->stateDuration = (integer) $stateDuration;

        return $this;
    }

    /**
     * Get StateDuration.
     *
     * @return int
     */
    public function getStateDuration()
    {
        return (integer) $this->stateDuration;
    }

    /**
     * Set StripClasses.
     *
     * @param array $stripeClasses
     *
     * @return $this
     */
    public function setStripeClasses($stripeClasses)
    {
        $this->stripeClasses = $stripeClasses;

        return $this;
    }

    /**
     * Get StripClasses.
     *
     * @return array
     */
    public function getStripeClasses()
    {
        return $this->stripeClasses;
    }

    /**
     * Set TabIndex.
     *
     * @param int $tabIndex
     *
     * @return $this
     */
    public function setTabIndex($tabIndex)
    {
        $this->tabIndex = (integer) $tabIndex;

        return $this;
    }

    /**
     * Get TabIndex.
     *
     * @return int
     */
    public function getTabIndex()
    {
        return (integer) $this->tabIndex;
    }
} 