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
     * @var null|int
     */
    protected $displayStart;

    /**
     * Define the table control elements to appear on the page and in what order.
     * DataTables default: lfrtip
     *
     * @var null|string
     */
    protected $dom;

    /**
     * Change the options in the page length select list.
     * DataTables default: [10, 25, 50, 100]
     *
     * @var null|array
     */
    protected $lengthMenu;

    /**
     * Initial order (sort) to apply to the table.
     * DataTables default: [[0, 'asc']]
     *
     * @var null|array
     */
    protected $order;

    /**
     * Control which cell the order event handler will be applied to in a column.
     * DataTables default: false
     *
     * @var null|bool
     */
    protected $orderCellsTop;

    /**
     * Highlight the columns being ordered in the table's body.
     * DataTables default: true
     *
     * @var null|bool
     */
    protected $orderClasses;

    /**
     * Ordering to always be applied to the table.
     *
     * @var null|array
     */
    protected $orderFixed;

    /**
     * Multiple column ordering ability control.
     * DataTables default: true
     *
     * @var null|bool
     */
    protected $orderMulti;

    /**
     * Change the initial page length (number of rows per page).
     * DataTables default: 10
     *
     * @var null|int
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
     * @var null|string
     */
    protected $pagingType;

    /**
     * Display component renderer types.
     *
     * @var null|string
     */
    protected $renderer;

    /**
     * Retrieve an existing DataTables instance.
     * DataTables default: false
     *
     * @var null|bool
     */
    protected $retrieve;

    /**
     * Data property name that DataTables will use to set tr element DOM IDs.
     * DataTables default: DT_RowId
     *
     * @var null|string
     */
    protected $rowId;

    /**
     * Allow the table to reduce in height when a limited number of rows are shown.
     * DataTables default: false
     *
     * @var null|bool
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
     * @var null|int
     */
    protected $stateDuration;

    /**
     * Set the zebra stripe class names for the rows in the table.
     *
     * @var null|array
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
            'display_start' => null,
            'dom' => null,
            'length_menu' => null,
            'order' => null,
            'order_cells_top' => null,
            'order_classes' => null,
            'order_fixed' => null,
            'order_multi' => null,
            'page_length' => null,
            'paging_type' => null,
            'renderer' => null,
            'retrieve' => null,
            'row_id' => null,
            'scroll_collapse' => null,
            'search_delay' => null,
            'state_duration' => null,
            'stripe_classes' => null,
            'individual_filtering' => false,
            'individual_filtering_position' => 'head'
        ));

        $resolver->setAllowedTypes('defer_loading', array('null', 'int', 'array'));
        $resolver->setAllowedTypes('display_start', array('null', 'int'));
        $resolver->setAllowedTypes('dom', array('null', 'string'));
        $resolver->setAllowedTypes('length_menu', array('null', 'array'));
        $resolver->setAllowedTypes('order', array('null', 'array'));
        $resolver->setAllowedTypes('order_cells_top', array('null', 'bool'));
        $resolver->setAllowedTypes('order_classes', array('null', 'bool'));
        $resolver->setAllowedTypes('order_fixed', array('null', 'array'));
        $resolver->setAllowedTypes('order_multi', array('null', 'bool'));
        $resolver->setAllowedTypes('page_length', array('null', 'int'));
        $resolver->setAllowedTypes('paging_type', array('null', 'string'));
        $resolver->setAllowedTypes('renderer', array('null', 'string'));
        $resolver->setAllowedTypes('retrieve', array('null', 'bool'));
        $resolver->setAllowedTypes('row_id', array('null', 'string'));
        $resolver->setAllowedTypes('scroll_collapse', array('null', 'bool'));
        $resolver->setAllowedTypes('search_delay', array('null', 'int'));
        $resolver->setAllowedTypes('state_duration', array('null', 'int'));
        $resolver->setAllowedTypes('stripe_classes', array('null', 'array'));
        $resolver->setAllowedTypes('individual_filtering', 'bool');
        $resolver->setAllowedTypes('individual_filtering_position', 'string');

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
        if (is_array($this->deferLoading)) {
            return $this->optionToJson($this->deferLoading);
        }

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
     * @return null|int
     */
    public function getDisplayStart()
    {
        return $this->displayStart;
    }

    /**
     * Set displayStart.
     *
     * @param null|int $displayStart
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
     * @return null|string
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * Set dom.
     *
     * @param null|string $dom
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
     * @return null|array
     */
    public function getLengthMenu()
    {
        if (is_array($this->lengthMenu)) {
            return $this->optionToJson($this->lengthMenu);
        }

        return $this->lengthMenu;
    }

    /**
     * Set lengthMenu.
     *
     * @param null|array $lengthMenu
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
     * @return null|array
     */
    public function getOrder()
    {
        if (is_array($this->order)) {
            return $this->optionToJson($this->order);
        }

        return $this->order;
    }

    /**
     * Set order.
     *
     * @param null|array $order
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
     * @return null|bool
     */
    public function isOrderCellsTop()
    {
        return $this->orderCellsTop;
    }

    /**
     * Set orderCellsTop.
     *
     * @param null|bool $orderCellsTop
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
     * @return null|bool
     */
    public function isOrderClasses()
    {
        return $this->orderClasses;
    }

    /**
     * Set orderClasses.
     *
     * @param null|bool $orderClasses
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
     * @return null|array
     */
    public function getOrderFixed()
    {
        if (is_array($this->orderFixed)) {
            return $this->optionToJson($this->orderFixed);
        }

        return $this->orderFixed;
    }

    /**
     * Set orderFixed.
     *
     * @param null|array $orderFixed
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
     * @return null|bool
     */
    public function isOrderMulti()
    {
        return $this->orderMulti;
    }

    /**
     * Set orderMulti.
     *
     * @param null|bool $orderMulti
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
     * @return null|int
     */
    public function getPageLength()
    {
        return $this->pageLength;
    }

    /**
     * Set pageLength.
     *
     * @param null|int $pageLength
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
     * @return null|string
     */
    public function getPagingType()
    {
        return $this->pagingType;
    }

    /**
     * Set pagingType.
     *
     * @param null|string $pagingType
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
     * @return null|string
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * Set renderer.
     *
     * @param null|string $renderer
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
     * @return null|bool
     */
    public function isRetrieve()
    {
        return $this->retrieve;
    }

    /**
     * Set retrieve.
     *
     * @param null|bool $retrieve
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
     * @return null|string
     */
    public function getRowId()
    {
        return $this->rowId;
    }

    /**
     * Set rowId.
     *
     * @param null|string $rowId
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
     * @return null|bool
     */
    public function isScrollCollapse()
    {
        return $this->scrollCollapse;
    }

    /**
     * Set scrollCollapse.
     *
     * @param null|bool $scrollCollapse
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
     * @return null|int
     */
    public function getStateDuration()
    {
        return $this->stateDuration;
    }

    /**
     * Set stateDuration.
     *
     * @param null|int $stateDuration
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
     * @return null|array
     */
    public function getStripeClasses()
    {
        if (is_array($this->stripeClasses)) {
            return $this->optionToJson($this->stripeClasses);
        }

        return $this->stripeClasses;
    }

    /**
     * Set stripeClasses.
     *
     * @param null|array $stripeClasses
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
