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

class Options
{
    use OptionsTrait;

    //--------------------------------------------------------------------------------------------------
    // DataTables - Options
    // --------------------
    // All DataTables Options are initialized with 'null'.
    // These 'null' initialized options uses the default value of the DataTables plugin.
    //--------------------------------------------------------------------------------------------------

    /**
     * Delay the loading of server-side data until second draw.
     * DataTables default: null
     * Default: null.
     *
     * @var array|int|null
     */
    protected $deferLoading;

    /**
     * Initial paging start point.
     * DataTables default: 0
     * Default: null.
     *
     * @var int|null
     */
    protected $displayStart;

    /**
     * Define the table control elements to appear on the page and in what order.
     * DataTables default: lfrtip
     * Default: null.
     *
     * @var string|null
     */
    protected $dom;

    /**
     * Change the options in the page length select list.
     * DataTables default: [10, 25, 50, 100]
     * Default: null.
     *
     * @var array|null
     */
    protected $lengthMenu;

    /**
     * Initial order (sort) to apply to the table.
     * DataTables default: [[0, 'asc']]
     * Default: null.
     *
     * @var array|null
     */
    protected $order;

    /**
     * Control which cell the order event handler will be applied to in a column.
     * DataTables default: false
     * Default: null.
     *
     * @var bool|null
     */
    protected $orderCellsTop;

    /**
     * Highlight the columns being ordered in the table's body.
     * DataTables default: true
     * Default: null.
     *
     * @var bool|null
     */
    protected $orderClasses;

    /**
     * Ordering to always be applied to the table.
     * Default: null.
     *
     * @var array|null
     */
    protected $orderFixed;

    /**
     * Multiple column ordering ability control.
     * DataTables default: true
     * Default: null.
     *
     * @var bool|null
     */
    protected $orderMulti;

    /**
     * Change the initial page length (number of rows per page).
     * DataTables default: 10
     * Default: null.
     *
     * @var int|null
     */
    protected $pageLength;

    /**
     * Pagination button display options.
     * The DataTables Plugin has some built-in paging button arrangements:
     *     numbers        - Page number buttons only
     *     simple         - 'Previous' and 'Next' buttons only
     *     simple_numbers - 'Previous' and 'Next' buttons, plus page numbers
     *     full           - 'First', 'Previous', 'Next' and 'Last' buttons
     *     full_numbers   - 'First', 'Previous', 'Next' and 'Last' buttons, plus page numbers.
     *
     * DataTables default: simple_numbers
     * Default: null
     *
     * @var string|null
     */
    protected $pagingType;

    /**
     * Display component renderer types.
     * Default: null.
     *
     * @var string|null
     */
    protected $renderer;

    /**
     * Retrieve an existing DataTables instance.
     * DataTables default: false
     * Default: null.
     *
     * @var bool|null
     */
    protected $retrieve;

    /**
     * Data property name that DataTables will use to set tr element DOM IDs.
     * DataTables default: DT_RowId
     * Default: null.
     *
     * @var string|null
     */
    protected $rowId;

    /**
     * Allow the table to reduce in height when a limited number of rows are shown.
     * DataTables default: false
     * Default: null.
     *
     * @var bool|null
     */
    protected $scrollCollapse;

    /**
     * Set a throttle frequency for searching.
     * DataTables default: null (400mS)
     * Default: null.
     *
     * @var int|null
     */
    protected $searchDelay;

    /**
     * Saved state validity duration.
     * DataTables default: 7200
     * Default: null.
     *
     * @var int|null
     */
    protected $stateDuration;

    /**
     * Set the zebra stripe class names for the rows in the table.
     * Default: null.
     *
     * @var array|null
     */
    protected $stripeClasses;

    //-------------------------------------------------
    // Custom Options
    //-------------------------------------------------

    /**
     * To define the style for the table.
     * Default: Style::BASE_STYLE.
     *
     * @var string
     */
    protected $classes;

    /**
     * Enable or disable individual filtering.
     * Default: false.
     *
     * @var bool
     */
    protected $individualFiltering;

    /**
     * Position of individual search filter ('head', 'foot' or 'both').
     * Default: 'head'.
     *
     * @var string
     */
    protected $individualFilteringPosition;

    /**
     * Determines whether to search in non-visible columns.
     * Default: false.
     *
     * @var bool
     */
    protected $searchInNonVisibleColumns;

    /**
     * The global search type.
     * Default: 'like'.
     *
     * @var string
     */
    protected $globalSearchType;

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
            'classes' => Style::BASE_STYLE,
            'individual_filtering' => false,
            'individual_filtering_position' => 'head',
            'search_in_non_visible_columns' => false,
            'global_search_type' => 'like',
        ]);

        $resolver->setAllowedTypes('defer_loading', ['null', 'int', 'array']);
        $resolver->setAllowedTypes('display_start', ['null', 'int']);
        $resolver->setAllowedTypes('dom', ['null', 'string']);
        $resolver->setAllowedTypes('length_menu', ['null', 'array']);
        $resolver->setAllowedTypes('order', ['null', 'array']);
        $resolver->setAllowedTypes('order_cells_top', ['null', 'bool']);
        $resolver->setAllowedTypes('order_classes', ['null', 'bool']);
        $resolver->setAllowedTypes('order_fixed', ['null', 'array']);
        $resolver->setAllowedTypes('order_multi', ['null', 'bool']);
        $resolver->setAllowedTypes('page_length', ['null', 'int']);
        $resolver->setAllowedTypes('paging_type', ['null', 'string']);
        $resolver->setAllowedTypes('renderer', ['null', 'string']);
        $resolver->setAllowedTypes('retrieve', ['null', 'bool']);
        $resolver->setAllowedTypes('row_id', ['null', 'string']);
        $resolver->setAllowedTypes('scroll_collapse', ['null', 'bool']);
        $resolver->setAllowedTypes('search_delay', ['null', 'int']);
        $resolver->setAllowedTypes('state_duration', ['null', 'int']);
        $resolver->setAllowedTypes('stripe_classes', ['null', 'array']);
        $resolver->setAllowedTypes('classes', 'string');
        $resolver->setAllowedTypes('individual_filtering', 'bool');
        $resolver->setAllowedTypes('individual_filtering_position', 'string');
        $resolver->setAllowedTypes('search_in_non_visible_columns', 'bool');
        $resolver->setAllowedTypes('global_search_type', 'string');

        $resolver->setAllowedValues('individual_filtering_position', ['head', 'foot', 'both']);
        $resolver->setAllowedValues('global_search_type', ['like', '%like', 'like%', 'notLike', 'eq', 'neq', 'lt', 'lte', 'gt', 'gte', 'in', 'notIn', 'isNull', 'isNotNull']);

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return array|int|null
     */
    public function getDeferLoading()
    {
        if (\is_array($this->deferLoading)) {
            return $this->optionToJson($this->deferLoading);
        }

        return $this->deferLoading;
    }

    /**
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
     * @return int|null
     */
    public function getDisplayStart()
    {
        return $this->displayStart;
    }

    /**
     * @param int|null $displayStart
     *
     * @return $this
     */
    public function setDisplayStart($displayStart)
    {
        $this->displayStart = $displayStart;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * @param string|null $dom
     *
     * @return $this
     */
    public function setDom($dom)
    {
        $this->dom = $dom;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getLengthMenu()
    {
        if (\is_array($this->lengthMenu)) {
            return $this->optionToJson($this->lengthMenu);
        }

        return $this->lengthMenu;
    }

    /**
     * @param array|null $lengthMenu
     *
     * @return $this
     */
    public function setLengthMenu($lengthMenu)
    {
        $this->lengthMenu = $lengthMenu;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getOrder()
    {
        if (\is_array($this->order)) {
            return $this->optionToJson($this->order);
        }

        return $this->order;
    }

    /**
     * @param array|null $order
     *
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isOrderCellsTop()
    {
        return $this->orderCellsTop;
    }

    /**
     * @param bool|null $orderCellsTop
     *
     * @return $this
     */
    public function setOrderCellsTop($orderCellsTop)
    {
        $this->orderCellsTop = $orderCellsTop;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isOrderClasses()
    {
        return $this->orderClasses;
    }

    /**
     * @param bool|null $orderClasses
     *
     * @return $this
     */
    public function setOrderClasses($orderClasses)
    {
        $this->orderClasses = $orderClasses;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getOrderFixed()
    {
        if (\is_array($this->orderFixed)) {
            return $this->optionToJson($this->orderFixed);
        }

        return $this->orderFixed;
    }

    /**
     * @param array|null $orderFixed
     *
     * @return $this
     */
    public function setOrderFixed($orderFixed)
    {
        $this->orderFixed = $orderFixed;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isOrderMulti()
    {
        return $this->orderMulti;
    }

    /**
     * @param bool|null $orderMulti
     *
     * @return $this
     */
    public function setOrderMulti($orderMulti)
    {
        $this->orderMulti = $orderMulti;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPageLength()
    {
        return $this->pageLength;
    }

    /**
     * @param int|null $pageLength
     *
     * @return $this
     */
    public function setPageLength($pageLength)
    {
        $this->pageLength = $pageLength;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPagingType()
    {
        return $this->pagingType;
    }

    /**
     * @param string|null $pagingType
     *
     * @return $this
     */
    public function setPagingType($pagingType)
    {
        $this->pagingType = $pagingType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param string|null $renderer
     *
     * @return $this
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isRetrieve()
    {
        return $this->retrieve;
    }

    /**
     * @param bool|null $retrieve
     *
     * @return $this
     */
    public function setRetrieve($retrieve)
    {
        $this->retrieve = $retrieve;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRowId()
    {
        return $this->rowId;
    }

    /**
     * @param string|null $rowId
     *
     * @return $this
     */
    public function setRowId($rowId)
    {
        $this->rowId = $rowId;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isScrollCollapse()
    {
        return $this->scrollCollapse;
    }

    /**
     * @param bool|null $scrollCollapse
     *
     * @return $this
     */
    public function setScrollCollapse($scrollCollapse)
    {
        $this->scrollCollapse = $scrollCollapse;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getSearchDelay()
    {
        return $this->searchDelay;
    }

    /**
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
     * @return int|null
     */
    public function getStateDuration()
    {
        return $this->stateDuration;
    }

    /**
     * @param int|null $stateDuration
     *
     * @return $this
     */
    public function setStateDuration($stateDuration)
    {
        $this->stateDuration = $stateDuration;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getStripeClasses()
    {
        if (\is_array($this->stripeClasses)) {
            return $this->optionToJson($this->stripeClasses);
        }

        return $this->stripeClasses;
    }

    /**
     * @param array|null $stripeClasses
     *
     * @return $this
     */
    public function setStripeClasses($stripeClasses)
    {
        $this->stripeClasses = $stripeClasses;

        return $this;
    }

    /**
     * @return string
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @param string $classes
     *
     * @return $this
     */
    public function setClasses($classes)
    {
        $this->classes = $classes;

        return $this;
    }

    /**
     * @return bool
     */
    public function isIndividualFiltering()
    {
        return $this->individualFiltering;
    }

    /**
     * @param bool $individualFiltering
     *
     * @return $this
     */
    public function setIndividualFiltering($individualFiltering)
    {
        $this->individualFiltering = $individualFiltering;

        return $this;
    }

    /**
     * @return string
     */
    public function getIndividualFilteringPosition()
    {
        return $this->individualFilteringPosition;
    }

    /**
     * @param string $individualFilteringPosition
     *
     * @return $this
     */
    public function setIndividualFilteringPosition($individualFilteringPosition)
    {
        $this->individualFilteringPosition = $individualFilteringPosition;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSearchInNonVisibleColumns()
    {
        return $this->searchInNonVisibleColumns;
    }

    /**
     * @param bool $searchInNonVisibleColumns
     *
     * @return $this
     */
    public function setSearchInNonVisibleColumns($searchInNonVisibleColumns)
    {
        $this->searchInNonVisibleColumns = $searchInNonVisibleColumns;

        return $this;
    }

    /**
     * @return string
     */
    public function getGlobalSearchType()
    {
        return $this->globalSearchType;
    }

    /**
     * @param string $globalSearchType
     *
     * @return $this
     */
    public function setGlobalSearchType($globalSearchType)
    {
        $this->globalSearchType = $globalSearchType;

        return $this;
    }
}
