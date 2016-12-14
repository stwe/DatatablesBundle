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

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Options
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
class Options extends AbstractViewOptions
{
    /**
     * Initial paging start point.
     *
     * @var integer
     */
    protected $displayStart;

    /**
     * Delay the loading of server-side data until second draw.
     *
     * @var integer
     */
    protected $deferLoading;

    /**
     * Define the table control elements to appear on the page and in what order.
     *
     * @var string
     */
    protected $dom;

    /**
     * Change the options in the page length select list.
     *
     * @var array
     */
    protected $lengthMenu;

    /**
     * Highlight the columns being ordered in the table's body.
     *
     * @var boolean
     */
    protected $orderClasses;

    /**
     * Initial order (sort) to apply to the table.
     *
     * @var array
     */
    protected $order;

    /**
     * Multiple column ordering ability control.
     *
     * @var boolean
     */
    protected $orderMulti;

    /**
     * Change the initial page length (number of rows per page).
     *
     * @var integer
     */
    protected $pageLength;

    /**
     * Pagination button display options.
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
     * Allow the table to reduce in height when a limited number of rows are shown.
     *
     * @var boolean
     */
    protected $scrollCollapse;

    /**
     * Set a throttle frequency for searching.
     *
     * @var integer
     */
    protected $searchDelay;

    /**
     * Saved state validity duration.
     *
     * @var integer
     */
    protected $stateDuration;

    /**
     * Set the zebra stripe class names for the rows in the table.
     *
     * @var array
     */
    protected $stripeClasses;

    /**
     * Table class names.
     *
     * @var string
     */
    protected $class;

    /**
     * Enable or disable individual filtering.
     *
     * @var boolean
     */
    protected $individualFiltering;

    /**
     * Position of individual search filter ("head", "foot" or "both").
     *
     * @var string
     */
    protected $individualFilteringPosition;

    /**
     * DataTables provides direct integration support (https://github.com/DataTables/Plugins/tree/master/integration) for:
     * - Bootstrap
     * - Foundation
     * - jQuery UI
     *
     * This flag is set in the layout, the "dom" and "renderer" options for the integration plugin (i.e. bootstrap).
     *
     * @var boolean
     */
    protected $useIntegrationOptions;

    /**
     * Force the use of the provided dom option, even if integration options are used.
     *
     * @var boolean
     */
    protected $forceDom;

    /**
     * Data property name that DataTables will use to set tr element DOM IDs.
     *
     * @var string
     */
    protected $rowId;

    /**
     * Count and show all results.
     *
     * @var boolean
     */
    protected $countAllResults;

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * Set one option.
     *
     * All the other parameters will not be altered.
     *
     * @param string $optionKey
     * @param mixed  $optionValue
     *
     * @return $this
     */
    public function setOption($optionKey, $optionValue)
    {
        $this->callingSettersWithOptions(array($optionKey => $optionValue), $this);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'display_start' => 0,
            'defer_loading' => -1,
            'dom' => 'lfrtip',
            'length_menu' => array(10, 25, 50, 100),
            'order_classes' => true,
            'order' => array(array(0, 'asc')),
            'order_multi' => true,
            'page_length' => 10,
            'paging_type' => Style::FULL_NUMBERS_PAGINATION,
            'renderer' => '',
            'scroll_collapse' => false,
            'search_delay' => 0,
            'state_duration' => 7200,
            'stripe_classes' => array(),
            'class' => Style::BASE_STYLE,
            'individual_filtering' => false,
            'individual_filtering_position' => 'head',
            'use_integration_options' => false,
            'force_dom' => false,
            'row_id' => '',
            'count_all_results' => true
        ));

        $resolver->setAllowedTypes('display_start', 'int');
        $resolver->setAllowedTypes('defer_loading', 'int');
        $resolver->setAllowedTypes('dom', 'string');
        $resolver->setAllowedTypes('length_menu', 'array');
        $resolver->setAllowedTypes('order_classes', 'bool');
        $resolver->setAllowedTypes('order', 'array');
        $resolver->setAllowedTypes('order_multi', 'bool');
        $resolver->setAllowedTypes('page_length', 'int');
        $resolver->setAllowedTypes('paging_type', 'string');
        $resolver->setAllowedTypes('renderer', 'string');
        $resolver->setAllowedTypes('scroll_collapse', 'bool');
        $resolver->setAllowedTypes('search_delay', 'int');
        $resolver->setAllowedTypes('state_duration', 'int');
        $resolver->setAllowedTypes('stripe_classes', 'array');
        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedTypes('individual_filtering', 'bool');
        $resolver->setAllowedTypes('individual_filtering_position', 'string');
        $resolver->setAllowedTypes('use_integration_options', 'bool');
        $resolver->setAllowedTypes('force_dom', 'bool');
        $resolver->setAllowedTypes('row_id', 'string');
        $resolver->setAllowedTypes('count_all_results', 'bool');

        $resolver->setAllowedValues('individual_filtering_position', array('head', 'foot', 'both'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set DisplayStart.
     *
     * @param int $displayStart
     *
     * @return $this
     */
    protected function setDisplayStart($displayStart)
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
     * Set DeferLoading.
     *
     * @param integer $deferLoading
     *
     * @return $this
     */
    protected function setDeferLoading($deferLoading)
    {
        $this->deferLoading = (integer) $deferLoading;

        return $this;
    }

    /**
     * Get DeferLoading.
     *
     * @return integer
     */
    public function getDeferLoading()
    {
        return (integer) $this->deferLoading;
    }

    /**
     * Set Dom.
     *
     * @param string $dom
     *
     * @return $this
     */
    protected function setDom($dom)
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
    protected function setLengthMenu(array $lengthMenu)
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
     * Set OrderClasses.
     *
     * @param boolean $orderClasses
     *
     * @return $this
     */
    protected function setOrderClasses($orderClasses)
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
     * Set Order.
     *
     * @param array $order
     *
     * @throws \Exception
     * @return $this
     */
    protected function setOrder(array $order)
    {
        foreach($order as $o) {
            if( !is_array($o) ||
                !array_key_exists(0, $o) ||
                !is_numeric($o[0]) ||
                !array_key_exists(1, $o) ||
                !in_array($o[1], array('desc', 'asc'))){
                throw new \Exception('setOrder(): Invalid array format.');
            }
        }
        
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
     * Set OrderMulti.
     *
     * @param boolean $orderMulti
     *
     * @return $this
     */
    protected function setOrderMulti($orderMulti)
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
    protected function setPageLength($pageLength)
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
    protected function setPagingType($pagingType)
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
     * @param string $renderer
     *
     * @return $this
     */
    protected function setRenderer($renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * Get Renderer.
     *
     * @return string
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * Set ScrollCollapse.
     *
     * @param boolean $scrollCollapse
     *
     * @return $this
     */
    protected function setScrollCollapse($scrollCollapse)
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
     * Set searchDelay.
     *
     * @param int $searchDelay
     *
     * @return $this
     */
    protected function setSearchDelay($searchDelay)
    {
        $this->searchDelay = $searchDelay;

        return $this;
    }

    /**
     * Get searchDelay.
     *
     * @return int
     */
    public function getSearchDelay()
    {
        return $this->searchDelay;
    }

    /**
     * Set StateDuration.
     *
     * @param int $stateDuration
     *
     * @return $this
     */
    protected function setStateDuration($stateDuration)
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
    protected function setStripeClasses(array $stripeClasses)
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
     * Set class.
     *
     * @param string $class
     *
     * @return $this
     */
    protected function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set individual filtering.
     *
     * @param boolean $individualFiltering
     *
     * @return $this
     */
    protected function setIndividualFiltering($individualFiltering)
    {
        $this->individualFiltering = (boolean) $individualFiltering;

        return $this;
    }

    /**
     * Get individual filtering.
     *
     * @return boolean
     */
    public function getIndividualFiltering()
    {
        return (boolean) $this->individualFiltering;
    }

    /**
     * Set individual filtering position.
     *
     * @param string $individualFilteringPosition
     *
     * @return $this
     */
    protected function setIndividualFilteringPosition($individualFilteringPosition)
    {
        $this->individualFilteringPosition = $individualFilteringPosition;

        return $this;
    }

    /**
     * Get individual filtering position.
     *
     * @return string
     */
    public function getIndividualFilteringPosition()
    {
        return $this->individualFilteringPosition;
    }

    /**
     * Set use integration options.
     *
     * @param boolean $useIntegrationOptions
     *
     * @return $this
     */
    protected function setUseIntegrationOptions($useIntegrationOptions)
    {
        $this->useIntegrationOptions = $useIntegrationOptions;

        return $this;
    }

    /**
     * Get use integration options.
     *
     * @return boolean
     */
    public function getUseIntegrationOptions()
    {
        return $this->useIntegrationOptions;
    }

    /**
     * Set force dom.
     *
     * @param boolean $forceDom
     *
     * @return $this
     */
    protected function setForceDom($forceDom)
    {
        $this->forceDom = $forceDom;

        return $this;
    }

    /**
     * Get force dom.
     *
     * @return boolean
     */
    public function getForceDom()
    {
        return $this->forceDom;
    }

    /**
     * Set rowId.
     *
     * @param string $rowId
     *
     * @return $this
     */
    protected function setRowId($rowId)
    {
        $this->rowId = $rowId;

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
     * Set count all results.
     *
     * @param boolean $countAllResults
     *
     * @return $this
     */
    protected function setCountAllResults($countAllResults)
    {
        $this->countAllResults = $countAllResults;

        return $this;
    }

    /**
     * Get count all results.
     *
     * @return boolean
     */
    public function getCountAllResults()
    {
        return $this->countAllResults;
    }
}
