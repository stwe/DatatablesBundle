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

use Symfony\Bundle\TwigBundle\TwigEngine;
use Sg\DatatablesBundle\Column\Column;
use Sg\DatatablesBundle\Column\ActionColumn;

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
     * The jQuery table id selector.
     *
     * @var string
     */
    protected $tableId;

    /**
     * Content for the table header cells.
     *
     * @var array
     */
    protected $tableHeaders;

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
        $this->tableId          = 'sg_datatable';
        $this->tableHeaders     = array();
        $this->columns          = array();
        $this->actionColumns    = array();
        $this->sAjaxSource      = '';
        $this->customizeOptions = array();

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
        $options['sDomOptions']      = $this->getSDomOptions();
        $options['tableId']          = $this->getTableId();
        $options['tableHeaders']     = $this->getTableHeaders();
        $options['columns']          = $this->getColumns();
        $options['actionColumns']    = $this->getActionColumns();
        $options['sAjaxSource']      = $this->getSAjaxSource();
        $options['customizeOptions'] = $this->getCustomizeOptions();

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
     * @param array $tableHeaders
     *
     * @return $this
     */
    public function setTableHeaders($tableHeaders)
    {
        $this->tableHeaders = $tableHeaders;

        return $this;
    }

    /**
     * @return array
     */
    public function getTableHeaders()
    {
        return $this->tableHeaders;
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
}

