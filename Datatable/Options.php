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
 * Class Options
 *
 * @package Sg\DatatablesBundle\Datatable
 */
class Options
{
    use OptionsTrait;

    //-------------------------------------------------
    // DataTables - Options
    //-------------------------------------------------

    /**
     * Delay the loading of server-side data until second draw.
     * DataTables default: null
     *
     * @var null|int|array
     */
    protected $deferLoading;

    /**
     * Initial paging start point.
     * DataTables default: 0
     *
     * @var int
     */
    protected $displayStart;

    /**
     * Define the table control elements to appear on the page and in what order.
     * DataTables default: lfrtip
     *
     * @var string
     */
    protected $dom;

    /**
     * Change the options in the page length select list.
     * DataTables default: [10, 25, 50, 100]
     *
     * @var array
     */
    protected $lengthMenu;

    /**
     * Initial order (sort) to apply to the table.
     * DataTables default: [[0, 'asc']]
     *
     * @var array
     */
    protected $order;

    /**
     * Control which cell the order event handler will be applied to in a column.
     * DataTables default: false
     *
     * @var bool
     */
    protected $orderCellsTop;

    /**
     * Highlight the columns being ordered in the table's body.
     * DataTables default: true
     *
     * @var bool
     */
    protected $orderClasses;

    /**
     * Ordering to always be applied to the table.
     *
     * @var array
     */
    protected $orderFixed;

    /**
     * Multiple column ordering ability control.
     * DataTables default: true
     *
     * @var bool
     */
    protected $orderMulti;

    /**
     * Change the initial page length (number of rows per page).
     * DataTables default: 10
     *
     * @var int
     */
    protected $pageLength;

    /**
     * Pagination button display options.
     * The DataTables Plugin has some built-in paging button arrangements:
     *     numbers        - Page number buttons only
     *     simple         - 'Previous' and 'Next' buttons only
     *     simple_numbers - 'Previous' and 'Next' buttons, plus page numbers
     *     full           - 'First', 'Previous', 'Next' and 'Last' buttons
     *     full_numbers   - 'First', 'Previous', 'Next' and 'Last' buttons, plus page numbers
     *
     * DataTables default: simple_numbers
     *
     * @var string
     */
    protected $pagingType;

    /**
     * Display component renderer types.
     *
     * @var string
     */
    protected $renderer;

    /**
     * Retrieve an existing DataTables instance.
     * DataTables default: false
     *
     * @var bool
     */
    protected $retrieve;

    /**
     * Data property name that DataTables will use to set tr element DOM IDs.
     * DataTables default: DT_RowId
     *
     * @var string
     */
    protected $rowId;

    /**
     * Allow the table to reduce in height when a limited number of rows are shown.
     * DataTables default: false
     *
     * @var bool
     */
    protected $scrollCollapse;

    /**
     * Set a throttle frequency for searching.
     * DataTables default: null (400mS)
     *
     * @var null|int
     */
    protected $searchDelay;

    /**
     * Saved state validity duration.
     * DataTables default: 7200
     *
     * @var int
     */
    protected $stateDuration;

    /**
     * Set the zebra stripe class names for the rows in the table.
     *
     * @var array
     */
    protected $stripeClasses;

    //-------------------------------------------------
    // Custom Options
    //-------------------------------------------------

    /**
     * Enable or disable individual filtering.
     *
     * @var bool
     */
    protected $individualFiltering;

    /**
     * Position of individual search filter ("head", "foot" or "both").
     *
     * @var string
     */
    protected $individualFilteringPosition;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Options constructor.
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
            'defer_loading' => null,
            'display_start' => 0,
            'dom' => 'lfrtip',
            'length_menu' => array(10, 25, 50, 100),
            'order' => array(array(0, 'asc')),
            'order_cells_top' => false,
            'order_classes' => true,
            'order_fixed' => array(),
            'order_multi' => true,
            'page_length' => 10,
            'paging_type' => 'simple_numbers',
            'renderer' => '',
            'retrieve' => false,
            'row_id' => 'id',
            'scoll_collapse' => false,
            'search_delay' => null,
            'state_duration' => 7200,
            'stripe_classes' => array(),
            'inidividual_filtering' => false,
            'inidividual_filtering_position' => 'head'
        ));

        $resolver->setAllowedTypes('defer_loading', array('null', 'int', 'array'));
        $resolver->setAllowedTypes('display_start', 'int');
        $resolver->setAllowedTypes('dom', 'string');
        $resolver->setAllowedTypes('length_menu', 'array');
        $resolver->setAllowedTypes('order', 'array');
        $resolver->setAllowedTypes('order_cells_top', 'bool');
        $resolver->setAllowedTypes('order_classes', 'bool');
        $resolver->setAllowedTypes('order_fixed', 'array');
        $resolver->setAllowedTypes('order_multi', 'bool');
        $resolver->setAllowedTypes('page_length', 'int');
        $resolver->setAllowedTypes('paging_type', 'string');
        $resolver->setAllowedTypes('renderer', 'string');
        $resolver->setAllowedTypes('retrieve', 'bool');
        $resolver->setAllowedTypes('row_id', 'string');
        $resolver->setAllowedTypes('scoll_collapse', 'bool');
        $resolver->setAllowedTypes('search_delay', array('null', 'int'));
        $resolver->setAllowedTypes('state_duration', 'int');
        $resolver->setAllowedTypes('stripe_classes', 'array');
        $resolver->setAllowedTypes('inidividual_filtering', 'bool');
        $resolver->setAllowedTypes('inidividual_filtering_position', 'string');

        $resolver->setAllowedValues('individual_filtering_position', array('head', 'foot', 'both'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get deferLoading.
     *
     * @return array|int|null
     */
    public function getDeferLoading()
    {
        return $this->deferLoading;
    }

    /**
     * Set deferLoading.
     *
     * @param array|int|null $deferLoading
     *
     * @return $this
     */
    public function setDeferLoading($deferLoading)
    {
        $this->deferLoading = $deferLoading;

        return $this;
    }

    /**
     * Get displayStart.
     *
     * @return int
     */
    public function getDisplayStart()
    {
        return $this->displayStart;
    }

    /**
     * Set displayStart.
     *
     * @param int $displayStart
     *
     * @return $this
     */
    public function setDisplayStart($displayStart)
    {
        $this->displayStart = $displayStart;

        return $this;
    }

    /**
     * Get dom.
     *
     * @return string
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * Set dom.
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
     * Get lengthMenu.
     *
     * @return array
     */
    public function getLengthMenu()
    {
        return $this->lengthMenu;
    }

    /**
     * Set lengthMenu.
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
     * Get order.
     *
     * @return array
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set order.
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
     * Get orderCellsTop.
     *
     * @return boolean
     */
    public function isOrderCellsTop()
    {
        return $this->orderCellsTop;
    }

    /**
     * Set orderCellsTop.
     *
     * @param boolean $orderCellsTop
     *
     * @return $this
     */
    public function setOrderCellsTop($orderCellsTop)
    {
        $this->orderCellsTop = $orderCellsTop;

        return $this;
    }

    /**
     * Get orderClasses.
     *
     * @return boolean
     */
    public function isOrderClasses()
    {
        return $this->orderClasses;
    }

    /**
     * Set orderClasses.
     *
     * @param boolean $orderClasses
     *
     * @return $this
     */
    public function setOrderClasses($orderClasses)
    {
        $this->orderClasses = $orderClasses;

        return $this;
    }

    /**
     * Get orderFixed.
     *
     * @return array
     */
    public function getOrderFixed()
    {
        return $this->orderFixed;
    }

    /**
     * Set orderFixed.
     *
     * @param array $orderFixed
     *
     * @return $this
     */
    public function setOrderFixed($orderFixed)
    {
        $this->orderFixed = $orderFixed;

        return $this;
    }

    /**
     * Get orderMulti.
     *
     * @return boolean
     */
    public function isOrderMulti()
    {
        return $this->orderMulti;
    }

    /**
     * Set orderMulti.
     *
     * @param boolean $orderMulti
     *
     * @return $this
     */
    public function setOrderMulti($orderMulti)
    {
        $this->orderMulti = $orderMulti;

        return $this;
    }

    /**
     * Get pageLength.
     *
     * @return int
     */
    public function getPageLength()
    {
        return $this->pageLength;
    }

    /**
     * Set pageLength.
     *
     * @param int $pageLength
     *
     * @return $this
     */
    public function setPageLength($pageLength)
    {
        $this->pageLength = $pageLength;

        return $this;
    }

    /**
     * Get pagingType.
     *
     * @return string
     */
    public function getPagingType()
    {
        return $this->pagingType;
    }

    /**
     * Set pagingType.
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
     * Get renderer.
     *
     * @return string
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * Set renderer.
     *
     * @param string $renderer
     *
     * @return $this
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * Get retrieve.
     *
     * @return boolean
     */
    public function isRetrieve()
    {
        return $this->retrieve;
    }

    /**
     * Set retrieve.
     *
     * @param boolean $retrieve
     *
     * @return $this
     */
    public function setRetrieve($retrieve)
    {
        $this->retrieve = $retrieve;

        return $this;
    }

    /**
     * Get rowId.
     *
     * @return string
     */
    public function getRowId()
    {
        return $this->rowId;
    }

    /**
     * Set rowId.
     *
     * @param string $rowId
     *
     * @return $this
     */
    public function setRowId($rowId)
    {
        $this->rowId = $rowId;

        return $this;
    }

    /**
     * Get scrollCollapse.
     *
     * @return boolean
     */
    public function isScrollCollapse()
    {
        return $this->scrollCollapse;
    }

    /**
     * Set scrollCollapse.
     *
     * @param boolean $scrollCollapse
     *
     * @return $this
     */
    public function setScrollCollapse($scrollCollapse)
    {
        $this->scrollCollapse = $scrollCollapse;

        return $this;
    }

    /**
     * Get searchDelay.
     *
     * @return int|null
     */
    public function getSearchDelay()
    {
        return $this->searchDelay;
    }

    /**
     * Set searchDelay.
     *
     * @param int|null $searchDelay
     *
     * @return $this
     */
    public function setSearchDelay($searchDelay)
    {
        $this->searchDelay = $searchDelay;

        return $this;
    }

    /**
     * Get stateDuration.
     *
     * @return int
     */
    public function getStateDuration()
    {
        return $this->stateDuration;
    }

    /**
     * Set stateDuration.
     *
     * @param int $stateDuration
     *
     * @return $this
     */
    public function setStateDuration($stateDuration)
    {
        $this->stateDuration = $stateDuration;

        return $this;
    }

    /**
     * Get stripeClasses.
     *
     * @return array
     */
    public function getStripeClasses()
    {
        return $this->stripeClasses;
    }

    /**
     * Set stripeClasses.
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
     * Get individualFiltering.
     *
     * @return boolean
     */
    public function isIndividualFiltering()
    {
        return $this->individualFiltering;
    }

    /**
     * Set individualFiltering.
     *
     * @param boolean $individualFiltering
     *
     * @return $this
     */
    public function setIndividualFiltering($individualFiltering)
    {
        $this->individualFiltering = $individualFiltering;

        return $this;
    }

    /**
     * Get individualFilteringPosition.
     *
     * @return string
     */
    public function getIndividualFilteringPosition()
    {
        return $this->individualFilteringPosition;
    }

    /**
     * Set individualFilteringPosition.
     *
     * @param string $individualFilteringPosition
     *
     * @return $this
     */
    public function setIndividualFilteringPosition($individualFilteringPosition)
    {
        $this->individualFilteringPosition = $individualFilteringPosition;

        return $this;
    }
}
