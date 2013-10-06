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

use Sg\DatatablesBundle\Column\Column;
use Sg\DatatablesBundle\Column\ActionColumn;

use Symfony\Bundle\TwigBundle\TwigEngine;

/**
 * Class AbstractDatatableView
 *
 * @package Sg\DatatablesBundle\Datatable
 */
abstract class AbstractDatatableView
{
    /**
     * The templating service.
     *
     * @var TwigEngine
     */
    protected $templating;

    /**
     * The name of the twig template.
     *
     * @var string
     */
    protected $template;

    /**
     * The css sDom parameter for:
     *  - dataTables_length,
     *  - dataTables_filter,
     *  - dataTables_info,
     *  - pagination
     *
     * @var array
     */
    protected $sDomOptions;

    /**
     * Number of rows to display on a single page when using pagination.
     *
     * @var integer
     */
    protected $iDisplayLength;

    /**
     * The jQuery table id selector.
     *
     * @var string
     */
    protected $tableId;

    /**
     * The aoColumns.
     *
     * @var array
     */
    protected $columns;

    /**
     * The action aoColumns.
     *
     * @var array
     */
    protected $actionColumns;

    /**
     * The sAjaxSource path.
     *
     * @var string
     */
    protected $sAjaxSource;

    /**
     * Array for custom options.
     *
     * @var array
     */
    protected $customizeOptions;

    /**
     * Enable or disable multiselect.
     *
     * @var boolean
     */
    protected $multiselect;

    /**
     * Enable or disable individual filtering.
     *
     * @var boolean
     */
    protected $individualFiltering;

    /**
     * Contains all bulk actions.
     *
     * @var array
     */
    protected $bulkActions;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param TwigEngine $templating
     */
    public function __construct(TwigEngine $templating)
    {
        $this->templating  = $templating;
        $this->template    = 'SgDatatablesBundle::default.html.twig';
        $this->sDomOptions = array(
            'sDomLength'     => 'span4',
            'sDomFilter'     => 'span8',
            'sDomInfo'       => 'span3',
            'sDomPagination' => 'span9'
        );
        $this->iDisplayLength      = 10;
        $this->tableId             = 'sg_datatable';
        $this->columns             = array();
        $this->actionColumns       = array();
        $this->sAjaxSource         = '';
        $this->customizeOptions    = array();
        $this->multiselect         = false;
        $this->individualFiltering = false;
        $this->bulkActions         = array();

        $this->build();
    }


    //-------------------------------------------------
    // Build view
    //-------------------------------------------------

    /**
     * @return mixed
     */
    abstract public function build();

    /**
     * Set all options for the twig template.
     *
     * @return string
     */
    public function createView()
    {
        $options = array();
        $options['sDomOptions']         = $this->getSDomOptions();
        $options['iDisplayLength']      = $this->getIDisplayLength();
        $options['tableId']             = $this->getTableId();
        $options['columns']             = $this->getColumns();
        $options['actionColumns']       = $this->getActionColumns();
        $options['sAjaxSource']         = $this->getSAjaxSource();
        $options['customizeOptions']    = $this->getCustomizeOptions();
        $options['multiselect']         = $this->getMultiselect();
        $options['individualFiltering'] = $this->getIndividualFiltering();
        $options['bulkActions']         = $this->getBulkActions();

        return $this->templating->render($this->getTemplate(), $options);
    }


    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * @param string $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set sDomOptions.
     *
     * @param array $sDomOptions
     *
     * @return $this
     * @throws \Exception
     */
    public function setSDomOptions($sDomOptions)
    {
        if (!array_key_exists('sDomLength', $sDomOptions)) {
            throw new \Exception('The option "sDomLength" must be set.');
        };

        if (!array_key_exists('sDomFilter', $sDomOptions)) {
            throw new \Exception('The option "sDomFilter" must be set.');
        };

        if (!array_key_exists('sDomInfo', $sDomOptions)) {
            throw new \Exception('The option "sDomInfo" must be set.');
        };

        if (!array_key_exists('sDomPagination', $sDomOptions)) {
            throw new \Exception('The option "sDomPagination" must be set.');
        };

        $this->sDomOptions = $sDomOptions;

        return $this;
    }

    /**
     * Get sDomOptions.
     *
     * @return array
     */
    public function getSDomOptions()
    {
        return $this->sDomOptions;
    }

    /**
     * @param int $iDisplayLength
     *
     * @return $this
     */
    public function setIDisplayLength($iDisplayLength)
    {
        $this->iDisplayLength = $iDisplayLength;

        return $this;
    }

    /**
     * @return int
     */
    public function getIDisplayLength()
    {
        return $this->iDisplayLength;
    }

    /**
     * @param string $tableId
     *
     * @return $this
     */
    public function setTableId($tableId)
    {
        $this->tableId = $tableId;

        return $this;
    }

    /**
     * @return string
     */
    public function getTableId()
    {
        return $this->tableId;
    }

    /**
     * Add a column.
     *
     * @param Column $column
     *
     * @return $this
     */
    public function addColumn($column)
    {
        $this->columns[] = $column;

        return $this;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return $this->columns;
    }

    /**
     * Add a action column.
     *
     * @param ActionColumn $actionColumn
     *
     * @return $this
     */
    public function addActionColumn($actionColumn)
    {
        $this->actionColumns[] = $actionColumn;

        return $this;
    }

    /**
     * Get action columns.
     *
     * @return array
     */
    public function getActionColumns()
    {
        return $this->actionColumns;
    }

    /**
     * @param string $sAjaxSource
     *
     * @return $this
     */
    public function setSAjaxSource($sAjaxSource)
    {
        $this->sAjaxSource = $sAjaxSource;

        return $this;
    }

    /**
     * @return string
     */
    public function getSAjaxSource()
    {
        return $this->sAjaxSource;
    }

    /**
     * @param array $customizeOptions
     *
     * @return $this
     */
    public function setCustomizeOptions($customizeOptions)
    {
        $this->customizeOptions = $customizeOptions;

        return $this;
    }

    /**
     * @return array
     */
    public function getCustomizeOptions()
    {
        return $this->customizeOptions;
    }

    /**
     * @param boolean $multiselect
     *
     * @return $this
     */
    public function setMultiselect($multiselect)
    {
        $this->multiselect = $multiselect;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getMultiselect()
    {
        return $this->multiselect;
    }

    /**
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
     * @return boolean
     */
    public function getIndividualFiltering()
    {
        return $this->individualFiltering;
    }

    /**
     * Add bulkAction.
     *
     * @param string $title The title for the form select field
     * @param string $route The route of the bulk action
     *
     * @return $this
     */
    public function addBulkAction($title, $route)
    {
        $this->bulkActions[$title] = $route;

        return $this;
    }

    /**
     * Set bulkAction.
     *
     * @return array
     */
    public function getBulkActions()
    {
        return $this->bulkActions;
    }
}

