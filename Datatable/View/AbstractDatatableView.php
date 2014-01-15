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

use Sg\DatatablesBundle\Column\ColumnBuilderInterface;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Exception;

/**
 * Class AbstractDatatableView
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
abstract class AbstractDatatableView implements DatatableViewInterface
{
    /**
     * The templating service.
     *
     * @var TwigEngine
     */
    protected $templating;

    /**
     * The datatable theme.
     *
     * @var DatatableThemeInterface
     */
    protected $theme;

    /**
     * Configure DataTables to use server-side processing.
     *
     * @var boolean
     */
    protected $bServerSide;

    /**
     * An array of data to use for the table.
     *
     * @var array
     */
    protected $aaData;

    /**
     * Enable or disable the display of a 'processing' indicator.
     *
     * @var boolean
     */
    protected $bProcessing;

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
     * A ColumnBuilderInterface.
     *
     * @var ColumnBuilderInterface
     */
    protected $columnBuilder;

    /**
     * The sAjaxSource path.
     *
     * @var string
     */
    protected $sAjaxSource;

    /**
     * The sAjaxSource parameters.
     *
     * @var string
     */
    protected $sAjaxSourceParameters;

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
     * @param TwigEngine             $templating    The templating service
     * @param array                  $layoutOptions The default layout options
     * @param ColumnBuilderInterface $columnBuilder A ColumnBuilderInterface
     */
    public function __construct(TwigEngine $templating, array $layoutOptions, ColumnBuilderInterface $columnBuilder)
    {
        $this->templating = $templating;
        $this->theme = null;
        $this->bServerSide = $layoutOptions['server_side'];
        $this->aaData = array();
        $this->bProcessing = $layoutOptions['processing'];
        $this->sDomOptions = null;
        $this->iDisplayLength = (int) $layoutOptions['display_length'];
        $this->tableId = $layoutOptions['table_id'];
        $this->columnBuilder = $columnBuilder;
        $this->sAjaxSource = '';
        $this->sAjaxSourceParameters = '';
        $this->customizeOptions = array();
        $this->multiselect = $layoutOptions['multiselect'];
        $this->individualFiltering = $layoutOptions['individual_filtering'];
        $this->bulkActions = array();
    }


    //-------------------------------------------------
    // DatatableViewInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    abstract public function buildDatatableView();

    /**
     * {@inheritdoc}
     */
    public function renderDatatableView()
    {
        $options = array();

        $options['bServerSide'] = $this->getBServerSide();
        $options['sAjaxSource'] = $this->getSAjaxSource();
        $options['sAjaxSourceParameters'] = $this->getSAjaxSourceParameters();

        if (true === $options['bServerSide']) {
            if ('' === $options['sAjaxSource']) {
                throw new Exception('The sAjaxSource parameter must be given!');
            }
        } else {
            $options['sAjaxSource'] = '';
            $options['aaData'] = $this->getAaData();
        }

        $options['bProcessing'] = $this->getBProcessing();
        $options['sDomOptions'] = $this->getSDomOptions();

        if (null === $options['sDomOptions']) {
            $options['sDomOptions'] = $this->theme->getSDomDefaultValues();
        }

        $options['iDisplayLength'] = $this->getIDisplayLength();
        $options['tableId'] = $this->getTableId();
        $options['columns'] = $this->columnBuilder->getColumns();
        $options['customizeOptions'] = $this->getCustomizeOptions();
        $options['multiselect'] = $this->getMultiselect();
        $options['individualFiltering'] = $this->getIndividualFiltering();
        $options['bulkActions'] = $this->getBulkActions();

        if (null === $this->theme) {
            throw new Exception('The datatable needs a theme!');
        }

        return $this->templating->render($this->theme->getTemplate(), $options);
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getName();


    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * Set bServerSide.
     *
     * @param boolean $bServerSide
     *
     * @return $this
     */
    public function setBServerSide($bServerSide)
    {
        $this->bServerSide = (boolean) $bServerSide;

        return $this;
    }

    /**
     * Get bServerSide.
     *
     * @return boolean
     */
    public function getBServerSide()
    {
        return (boolean) $this->bServerSide;
    }

    /**
     * Set aaData.
     *
     * @param array $aaData
     *
     * @return $this
     */
    public function setAaData($aaData)
    {
        $this->aaData = $aaData;

        return $this;
    }

    /**
     * Get aaData.
     *
     * @return array
     */
    public function getAaData()
    {
        return $this->aaData;
    }

    /**
     * Set bProcessing.
     *
     * @param boolean $bProcessing
     */
    public function setBProcessing($bProcessing)
    {
        $this->bProcessing = (boolean) $bProcessing;
    }

    /**
     * Get bProcessing.
     *
     * @return boolean
     */
    public function getBProcessing()
    {
        return (boolean) $this->bProcessing;
    }

    /**
     * Set sDomOptions.
     *
     * @param array $sDomOptions
     *
     * @return $this
     * @throws Exception
     */
    public function setSDomOptions(array $sDomOptions)
    {
        if (!array_key_exists('sDomLength', $sDomOptions)) {
            throw new Exception('The option "sDomLength" must be set.');
        };

        if (!array_key_exists('sDomFilter', $sDomOptions)) {
            throw new Exception('The option "sDomFilter" must be set.');
        };

        if (!array_key_exists('sDomInfo', $sDomOptions)) {
            throw new Exception('The option "sDomInfo" must be set.');
        };

        if (!array_key_exists('sDomPagination', $sDomOptions)) {
            throw new Exception('The option "sDomPagination" must be set.');
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
     * Set iDisplayLength.
     *
     * @param int $iDisplayLength
     *
     * @return $this
     */
    public function setIDisplayLength($iDisplayLength)
    {
        $this->iDisplayLength = (int) $iDisplayLength;

        return $this;
    }

    /**
     * Get iDisplayLength.
     *
     * @return int
     */
    public function getIDisplayLength()
    {
        return (int) $this->iDisplayLength;
    }

    /**
     * Set tableId.
     *
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
     * Get tableId.
     *
     * @return string
     */
    public function getTableId()
    {
        return $this->tableId;
    }

    /**
     * Set columnBuilder.
     *
     * @param ColumnBuilderInterface $columnBuilder
     *
     * @return $this
     */
    public function setColumnBuilder(ColumnBuilderInterface $columnBuilder)
    {
        $this->columnBuilder = $columnBuilder;

        return $this;
    }

    /**
     * Get columnBuilder.
     *
     * @return ColumnBuilderInterface
     */
    public function getColumnBuilder()
    {
        return $this->columnBuilder;
    }

    /**
     * Set sAjaxSource.
     *
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
     * Get sAjaxSource.
     *
     * @return string
     */
    public function getSAjaxSource()
    {
        return $this->sAjaxSource;
    }

    /**
     * Gets the value of sAjaxSourceParameters.
     *
     * @return mixed
     */
    public function getSAjaxSourceParameters()
    {
        return $this->sAjaxSourceParameters;
    }

    /**
     * Sets the value of sAjaxSourceParameters.
     *
     * @param mixed $sAjaxSourceParameters the route parameters
     *
     * @return $this
     */
    public function setSAjaxSourceParameters($sAjaxSourceParameters)
    {
        $this->sAjaxSourceParameters = $sAjaxSourceParameters;

        return $this;
    }

    /**
     * Sets the value of sAjaxSourceParameters.
     *
     * @param string $key   The route parameter key
     * @param mixed  $value The route parameter value
     *
     * @return $this
     */
    public function setSAjaxSourceParameter($key, $value)
    {
        $this->sAjaxSourceParameters[$key] = $value;

        return $this;
    }

    /**
     * Set customizeOptions.
     *
     * @param array $customizeOptions
     *
     * @return $this
     */
    public function setCustomizeOptions(array $customizeOptions)
    {
        $this->customizeOptions = $customizeOptions;

        return $this;
    }

    /**
     * Get customizeOptions.
     *
     * @return array
     */
    public function getCustomizeOptions()
    {
        return $this->customizeOptions;
    }

    /**
     * Set multiselect.
     *
     * @param boolean $multiselect
     *
     * @return $this
     */
    public function setMultiselect($multiselect)
    {
        $this->multiselect = (boolean) $multiselect;

        return $this;
    }

    /**
     * Get multiselect.
     *
     * @return boolean
     */
    public function getMultiselect()
    {
        return (boolean) $this->multiselect;
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
        $this->individualFiltering = (boolean) $individualFiltering;

        return $this;
    }

    /**
     * Get individualFiltering.
     *
     * @return boolean
     */
    public function getIndividualFiltering()
    {
        return (boolean) $this->individualFiltering;
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

