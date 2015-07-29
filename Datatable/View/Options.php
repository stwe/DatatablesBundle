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

use Exception;

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
     * Define the table control elements to appear on the page and in what order with datatable extension.
     *
     * @var string
     */
    private $sdom;

    /**
     * Change the options in the page length select list.
     *
     * @var array
     */
    private $lengthMenu;

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
     * @var string
     */
    private $renderer;

    /**
     * Allow the table to reduce in height when a limited number of rows are shown.
     *
     * @var boolean
     */
    private $scrollCollapse;

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
     * Enable the Responsive extension for DataTables.
     *
     * @var boolean
     */
    private $responsive;
    /**
     * Enable the TableTools extension for DataTables.
     *
     * @var boolean
     */
    private $tabletools;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->displayStart = 0;
        $this->dom = "lfrtip";
        $this->sDom = "lfrtip";
        $this->lengthMenu = array(10, 25, 50, 100);
        $this->orderClasses = true;
        $this->order = array("column" => 0, "direction" => "asc");
        $this->orderMulti = true;
        $this->pageLength = 10;
        $this->pagingType = self::FULL_NUMBERS_PAGINATION;
        $this->renderer = "";
        $this->scrollCollapse = false;
        $this->stateDuration = 7200;
        $this->stripeClasses = array();
        $this->responsive = false;
        $this->tabletools = false;
        
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
     * Set sdom.
     *
     * @param string $sdom
     *
     * @return $this
     */
    public function setSdom($sdom)
    {
        $this->sdom = $sdom;

        return $this;
    }

    /**
     * Get sdom.
     *
     * @return string
     */
    public function getSdom()
    {
        return $this->sdom;
    }
    
    /**
     * Set LengthMenu.
     *
     * @param array $lengthMenu
     *
     * @return $this
     */
    public function setLengthMenu(array $lengthMenu)
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
     * Set Order.
     *
     * @param array $order
     *
     * @throws Exception
     * @return $this
     */
    public function setOrder(array $order)
    {
        if (true === array_key_exists("column", $order) && true === array_key_exists("direction", $order)) {
            $this->order = $order;
        } else {
            throw new Exception("Invalid array format.");
        }

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
    public function setStripeClasses(array $stripeClasses)
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
     * Set responsive.
     *
     * @param boolean $responsive
     *
     * @return $this
     */
    public function setResponsive($responsive)
    {
        $this->responsive = (boolean) $responsive;

        return $this;
    }

    /**
     * Get responsive.
     *
     * @return boolean
     */
    public function getResponsive()
    {
        return (boolean) $this->responsive;
    }
    
    /**
     * Set tabletools.
     *
     * @param boolean $tabletools
     *
     * @return $this
     */
    public function setTabletools($tabletools)
    {
        $this->tabletools = (boolean) $tabletools;

        return $this;
    }

    /**
     * Get tabletools.
     *
     * @return boolean
     */
    public function getTabletools()
    {
        return (boolean) $this->tabletools;
    }
}
