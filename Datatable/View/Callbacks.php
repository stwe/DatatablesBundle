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
 * Class Callbacks
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
class Callbacks extends AbstractViewOptions
{
    /**
     * Callback for whenever a TR element is created for the table's body.
     *
     * @var array
     */
    protected $createdRow;

    /**
     * Function that is called every time DataTables performs a draw.
     *
     * @var array
     */
    protected $drawCallback;

    /**
     * Footer display callback function.
     *
     * @var array
     */
    protected $footerCallback;

    /**
     * Number formatting callback function.
     *
     * @var array
     */
    protected $formatNumber;

    /**
     * Header display callback function.
     *
     * @var array
     */
    protected $headerCallback;

    /**
     * Table summary information display callback.
     *
     * @var array
     */
    protected $infoCallback;

    /**
     * Initialisation complete callback.
     *
     * @var array
     */
    protected $initComplete;

    /**
     * Pre-draw callback.
     *
     * @var array
     */
    protected $preDrawCallback;

    /**
     * Row draw callback.
     *
     * @var array
     */
    protected $rowCallback;

    /**
     * Callback that defines where and how a saved state should be loaded.
     *
     * @var array
     */
    protected $stateLoadCallback;

    /**
     * State loaded callback.
     *
     * @var array
     */
    protected $stateLoaded;

    /**
     * State loaded - data manipulation callback.
     *
     * @var array
     */
    protected $stateLoadParams;

    /**
     * Callback that defines how the table state is stored and where.
     *
     * @var array
     */
    protected $stateSaveCallback;

    /**
     * State save - data manipulation callback.
     *
     * @var array
     */
    protected $stateSaveParams;

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'created_row' => array(),
            'draw_callback' => array(),
            'footer_callback' => array(),
            'format_number' => array(),
            'header_callback' => array(),
            'info_callback' => array(),
            'init_complete' => array(),
            'pre_draw_callback' => array(),
            'row_callback' => array(),
            'state_load_callback' => array(),
            'state_loaded' => array(),
            'state_load_params' => array(),
            'state_save_callback' => array(),
            'state_save_params' => array(),
        ));

        $resolver->setAllowedTypes('created_row', 'array');
        $resolver->setAllowedTypes('draw_callback', 'array');
        $resolver->setAllowedTypes('footer_callback', 'array');
        $resolver->setAllowedTypes('format_number', 'array');
        $resolver->setAllowedTypes('header_callback', 'array');
        $resolver->setAllowedTypes('info_callback', 'array');
        $resolver->setAllowedTypes('init_complete', 'array');
        $resolver->setAllowedTypes('pre_draw_callback', 'array');
        $resolver->setAllowedTypes('row_callback', 'array');
        $resolver->setAllowedTypes('state_load_callback', 'array');
        $resolver->setAllowedTypes('state_loaded', 'array');
        $resolver->setAllowedTypes('state_load_params', 'array');
        $resolver->setAllowedTypes('state_save_callback', 'array');
        $resolver->setAllowedTypes('state_save_params', 'array');

        $this->nestedOptionsResolver = new OptionsResolver();

        return $this;
    }

    /**
     * Configure and resolve nested options.
     *
     * @param array $options
     *
     * @return $this
     */
    public function configureAndResolveNestedOptions(array $options)
    {
        $this->nestedOptionsResolver->setDefaults(array(
            'template' => '',
            'vars' => null,
        ));

        $this->nestedOptionsResolver->setAllowedTypes('template', 'string');
        $this->nestedOptionsResolver->setAllowedTypes('vars', array('array', 'null'));

        $this->nestedOptionsResolver->resolve($options);

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get CreatedRow.
     *
     * @return array
     */
    public function getCreatedRow()
    {
        return $this->createdRow;
    }

    /**
     * Set CreatedRow.
     *
     * @param array $createdRow
     *
     * @return $this
     */
    protected function setCreatedRow(array $createdRow)
    {
        $this->createdRow = $createdRow;

        return $this;
    }

    /**
     * Get DrawCallback.
     *
     * @return array
     */
    public function getDrawCallback()
    {
        return $this->drawCallback;
    }

    /**
     * Set DrawCallback.
     *
     * @param array $drawCallback
     *
     * @return $this
     */
    protected function setDrawCallback(array $drawCallback)
    {
        $this->drawCallback = $drawCallback;

        return $this;
    }

    /**
     * Get FooterCallback.
     *
     * @return array
     */
    public function getFooterCallback()
    {
        return $this->footerCallback;
    }

    /**
     * Set FooterCallback.
     *
     * @param array $footerCallback
     *
     * @return $this
     */
    protected function setFooterCallback(array $footerCallback)
    {
        $this->footerCallback = $footerCallback;

        return $this;
    }

    /**
     * Get FormatNumber.
     *
     * @return array
     */
    public function getFormatNumber()
    {
        return $this->formatNumber;
    }

    /**
     * Set FormatNumber.
     *
     * @param array $formatNumber
     *
     * @return $this
     */
    protected function setFormatNumber(array $formatNumber)
    {
        $this->formatNumber = $formatNumber;

        return $this;
    }

    /**
     * Get HeaderCallback.
     *
     * @return array
     */
    public function getHeaderCallback()
    {
        return $this->headerCallback;
    }

    /**
     * Set HeaderCallback.
     *
     * @param array $headerCallback
     *
     * @return $this
     */
    protected function setHeaderCallback(array $headerCallback)
    {
        $this->headerCallback = $headerCallback;

        return $this;
    }

    /**
     * Get InfoCallback.
     *
     * @return array
     */
    public function getInfoCallback()
    {
        return $this->infoCallback;
    }

    /**
     * Set InfoCallback.
     *
     * @param array $infoCallback
     *
     * @return $this
     */
    protected function setInfoCallback(array $infoCallback)
    {
        $this->infoCallback = $infoCallback;

        return $this;
    }

    /**
     * Get InitComplete.
     *
     * @return array
     */
    public function getInitComplete()
    {
        return $this->initComplete;
    }

    /**
     * Set InitComplete.
     *
     * @param array $initComplete
     *
     * @return $this
     */
    protected function setInitComplete(array $initComplete)
    {
        $this->initComplete = $initComplete;

        return $this;
    }

    /**
     * Get PreDrawCallback.
     *
     * @return array
     */
    public function getPreDrawCallback()
    {
        return $this->preDrawCallback;
    }

    /**
     * Set PreDrawCallback.
     *
     * @param array $preDrawCallback
     *
     * @return $this
     */
    protected function setPreDrawCallback(array $preDrawCallback)
    {
        $this->preDrawCallback = $preDrawCallback;

        return $this;
    }

    /**
     * Get RowCallback.
     *
     * @return array
     */
    public function getRowCallback()
    {
        return $this->rowCallback;
    }

    /**
     * Set RowCallback.
     *
     * @param array $rowCallback
     *
     * @return $this
     */
    protected function setRowCallback(array $rowCallback)
    {
        $this->rowCallback = $rowCallback;

        return $this;
    }

    /**
     * Get StateLoadCallback.
     *
     * @return array
     */
    public function getStateLoadCallback()
    {
        return $this->stateLoadCallback;
    }

    /**
     * Set StateLoadCallback.
     *
     * @param array $stateLoadCallback
     *
     * @return $this
     */
    protected function setStateLoadCallback(array $stateLoadCallback)
    {
        $this->stateLoadCallback = $stateLoadCallback;

        return $this;
    }

    /**
     * Get StateLoaded.
     *
     * @return array
     */
    public function getStateLoaded()
    {
        return $this->stateLoaded;
    }

    /**
     * Set StateLoaded.
     *
     * @param array $stateLoaded
     *
     * @return $this
     */
    protected function setStateLoaded(array $stateLoaded)
    {
        $this->stateLoaded = $stateLoaded;

        return $this;
    }

    /**
     * Get StateLoadParams.
     *
     * @return array
     */
    public function getStateLoadParams()
    {
        return $this->stateLoadParams;
    }

    /**
     * Set StateLoadParams.
     *
     * @param array $stateLoadParams
     *
     * @return $this
     */
    protected function setStateLoadParams(array $stateLoadParams)
    {
        $this->stateLoadParams = $stateLoadParams;

        return $this;
    }

    /**
     * Get StateSaveCallback.
     *
     * @return array
     */
    public function getStateSaveCallback()
    {
        return $this->stateSaveCallback;
    }

    /**
     * Set StateSaveCallback.
     *
     * @param array $stateSaveCallback
     *
     * @return $this
     */
    protected function setStateSaveCallback(array $stateSaveCallback)
    {
        $this->stateSaveCallback = $stateSaveCallback;

        return $this;
    }

    /**
     * Get StateSaveParams.
     *
     * @return array
     */
    public function getStateSaveParams()
    {
        return $this->stateSaveParams;
    }

    /**
     * Set StateSaveParams.
     *
     * @param array $stateSaveParams
     *
     * @return $this
     */
    protected function setStateSaveParams(array $stateSaveParams)
    {
        $this->stateSaveParams = $stateSaveParams;

        return $this;
    }
}
