<?php

namespace Sg\DatatablesBundle\Datatable;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Sg\DatatablesBundle\Column\Column;

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
     * The sAjaxSource path.
     *
     * @var string
     */
    protected $sAjaxSource;

    /**
     * The show Path for the entity.
     *
     * @var string
     */
    protected $showPath;

    /**
     * The edit Path for the entity.
     *
     * @var string
     */
    protected $editPath;

    /**
     * The delete Path for the entity.
     *
     * @var string
     */
    protected $deletePath;

    /**
     * Array for custom options.
     *
     * @var array
     */
    protected $customizeOptions;


    //-------------------------------------------------
    // Ctor
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
        $this->sAjaxSource      = '';
        $this->showPath         = '';
        $this->editPath         = '';
        $this->deletePath       = '';
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
        $options['columns']          = $this->getColumnsOptions();
        $options['sAjaxSource']      = $this->getSAjaxSource();
        $options['showPath']         = $this->getShowPath();
        $options['editPath']         = $this->getEditPath();
        $options['deletePath']       = $this->getDeletePath();
        $options['customizeOptions'] = $this->getCustomizeOptions();

        return $this->templating->render($this->getTemplate(), $options);
    }


    //-------------------------------------------------
    // Columns functions
    //-------------------------------------------------

    /**
     * @param Column $column
     *
     * @return AbstractDatatableView
     */
    public function addColumn($column)
    {
        $this->columns[] = $column;

        return $this;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get columns options.
     *
     * @return array
     */
    private function getColumnsOptions()
    {
        $mData = array();

        /**
         * @var \Sg\DatatablesBundle\Column\Column $column
         */
        foreach ($this->columns as $column) {

            $property = array(
                'mData'                => $column->getMData(),
                'sName'                => $column->getSName(),
                'sClass'               => $column->getSClass(),
                'mRender'              => $column->getMRender(),
                'renderArray'          => $column->getRenderArray(),
                'renderArrayFieldName' => $column->getRenderArrayFieldName(),
                'sWidth'               => $column->getSWidth(),
                'bSearchable'          => $column->getBSearchable(),
                'bSortable'            => $column->getBSortable()
            );

            array_push($mData, $property);
        }

        return $mData;
    }


    //-------------------------------------------------
    // sDom functions
    //-------------------------------------------------

    /**
     * @param array $sDomOptions
     *
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
    }

    /**
     * @return array
     */
    public function getSDomOptions()
    {
        return $this->sDomOptions;
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @param string $sAjaxSource
     */
    public function setSAjaxSource($sAjaxSource)
    {
        $this->sAjaxSource = $sAjaxSource;
    }

    /**
     * @return string
     */
    public function getSAjaxSource()
    {
        return $this->sAjaxSource;
    }

    /**
     * @param array $tableHeaders
     */
    public function setTableHeaders($tableHeaders)
    {
        $this->tableHeaders = $tableHeaders;
    }

    /**
     * @return array
     */
    public function getTableHeaders()
    {
        return $this->tableHeaders;
    }

    /**
     * @param string $tableId
     */
    public function setTableId($tableId)
    {
        $this->tableId = $tableId;
    }

    /**
     * @return string
     */
    public function getTableId()
    {
        return $this->tableId;
    }

    /**
     * @param string $deletePath
     */
    public function setDeletePath($deletePath)
    {
        $this->deletePath = $deletePath;
    }

    /**
     * @return string
     */
    public function getDeletePath()
    {
        return $this->deletePath;
    }

    /**
     * @param string $editPath
     */
    public function setEditPath($editPath)
    {
        $this->editPath = $editPath;
    }

    /**
     * @return string
     */
    public function getEditPath()
    {
        return $this->editPath;
    }

    /**
     * @param string $showPath
     */
    public function setShowPath($showPath)
    {
        $this->showPath = $showPath;
    }

    /**
     * @return string
     */
    public function getShowPath()
    {
        return $this->showPath;
    }

    /**
     * @param array $customizeOptions
     */
    public function setCustomizeOptions($customizeOptions)
    {
        $this->customizeOptions = $customizeOptions;
    }

    /**
     * @return array
     */
    public function getCustomizeOptions()
    {
        return $this->customizeOptions;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }
}

