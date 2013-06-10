<?php

namespace Sg\DatatablesBundle\Datatable;

use Twig_Environment as Twig;

/**
 * Class AbstractDatatableView
 *
 * @package Sg\DatatablesBundle\Datatable
 */
abstract class AbstractDatatableView
{
    /**
     * A Twig instance.
     *
     * @var Twig
     */
    private $twig;

    /**
     * The Twig template.
     *
     * @var string
     */
    private $template;

    /**
     * The css sDom parameter for:
     *  - dataTables_length,
     *  - dataTables_filter,
     *  - dataTables_info,
     *  - pagination
     *
     * @var array
     */
    private $sDomOptions;

    /**
     * The table id selector.
     *
     * @var string
     */
    private $tableId;

    /**
     * Content for the table header cells.
     *
     * @var array
     */
    private $tableHeaders;

    /**
     * The aoColumns fields.
     *
     * @var array
     */
    private $fields;

    /**
     * The sAjaxSource path.
     *
     * @var string
     */
    private $sAjaxSource;

    /**
     * The show Path for the entity.
     *
     * @var string
     */
    private $showPath;

    /**
     * The edit Path for the entity.
     *
     * @var string
     */
    private $editPath;

    /**
     * The delete Path for the entity.
     *
     * @var string
     */
    private $deletePath;

    /**
     * Array for custom options.
     *
     * @var array
     */
    private $customizeOptions;


    //-------------------------------------------------
    // Ctor
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param Twig $twig A Twig instance
     */
    public function __construct(Twig $twig)
    {
        $this->twig        = $twig;
        $this->template    = 'SgDatatablesBundle::default.html.twig';
        $this->sDomOptions = array(
            'sDomLength'     => 'span4',
            'sDomFilter'     => 'span8',
            'sDomInfo'       => 'span3',
            'sDomPagination' => 'span9'
        );

        $this->tableId          = 'sg_datatable';
        $this->tableHeaders     = array();
        $this->fields           = array();
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
        $options['id']               = $this->getTableId();
        $options['sAjaxSource']      = $this->getSAjaxSource();
        $options['tableHeaders']     = $this->getTableHeaders();
        $options['sDomOptions']      = $this->getSDomOptions();
        $options['fields']           = $this->getFieldsOptions();
        $options['showPath']         = $this->getShowPath();
        $options['editPath']         = $this->getEditPath();
        $options['deletePath']       = $this->getDeletePath();
        $options['customizeOptions'] = $this->getCustomizeOptions();

        return $this->twig->render($this->getTemplate(), $options);
    }


    //-------------------------------------------------
    // Field functions
    //-------------------------------------------------

    /**
     * @param Field $field
     *
     * @return AbstractDatatableView
     */
    public function addField($field)
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Get fields options.
     *
     * @return array
     */
    private function getFieldsOptions()
    {
        $mData = array();

        /**
         * @var \Sg\DatatablesBundle\Datatable\Field $field
         */
        foreach ($this->fields as $field) {

            $property = array(
                'mData'                => $field->getMData(),
                'sName'                => $field->getSName(),
                'sClass'               => $field->getSClass(),
                'mRender'              => $field->getMRender(),
                'renderArray'          => $field->getRenderArray(),
                'renderArrayFieldName' => $field->getRenderArrayFieldName(),
                'sWidth'               => $field->getSWidth(),
                'bSearchable'          => $field->getBSearchable(),
                'bSortable'            => $field->getBSortable()
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

