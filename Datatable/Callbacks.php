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
 * Class Callbacks
 *
 * @package Sg\DatatablesBundle\Datatable
 */
class Callbacks
{
    /**
     * Use the OptionsResolver.
     */
    use OptionsTrait;

    //-------------------------------------------------
    // DataTables - Callbacks
    //-------------------------------------------------

    /**
     * Callback for whenever a TR element is created for the table's body.
     *
     * @var null|array
     */
    protected $createdRow;

    /**
     * Function that is called every time DataTables performs a draw.
     *
     * @var null|array
     */
    protected $drawCallback;

    /**
     * Footer display callback function.
     *
     * @var null|array
     */
    protected $footerCallback;

    /**
     * Number formatting callback function.
     *
     * @var null|array
     */
    protected $formatNumber;

    /**
     * Header display callback function.
     *
     * @var null|array
     */
    protected $headerCallback;

    /**
     * Table summary information display callback.
     *
     * @var null|array
     */
    protected $infoCallback;

    /**
     * Initialisation complete callback.
     *
     * @var null|array
     */
    protected $initComplete;

    /**
     * Pre-draw callback.
     *
     * @var null|array
     */
    protected $preDrawCallback;

    /**
     * Row draw callback.
     *
     * @var null|array
     */
    protected $rowCallback;

    /**
     * Callback that defines where and how a saved state should be loaded.
     *
     * @var null|array
     */
    protected $stateLoadCallback;

    /**
     * State loaded callback.
     *
     * @var null|array
     */
    protected $stateLoaded;

    /**
     * State loaded - data manipulation callback.
     *
     * @var null|array
     */
    protected $stateLoadParams;

    /**
     * Callback that defines how the table state is stored and where.
     *
     * @var null|array
     */
    protected $stateSaveCallback;

    /**
     * State save - data manipulation callback.
     *
     * @var null|array
     */
    protected $stateSaveParams;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Callbacks constructor.
     */
    public function __construct()
    {
        $this->initOptions();
    }

    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'created_row' => null,
            'draw_callback' => null,
            'footer_callback' => null,
            'format_number' => null,
            'header_callback' => null,
            'info_callback' => null,
            'init_complete' => null,
            'pre_draw_callback' => null,
            'row_callback' => null,
            'state_load_callback' => null,
            'state_loaded' => null,
            'state_load_params' => null,
            'state_save_callback' => null,
            'state_save_params' => null,
        ));

        $resolver->setAllowedTypes('created_row', array('null', 'array'));
        $resolver->setAllowedTypes('draw_callback', array('null', 'array'));
        $resolver->setAllowedTypes('footer_callback', array('null', 'array'));
        $resolver->setAllowedTypes('format_number', array('null', 'array'));
        $resolver->setAllowedTypes('header_callback', array('null', 'array'));
        $resolver->setAllowedTypes('info_callback', array('null', 'array'));
        $resolver->setAllowedTypes('init_complete', array('null', 'array'));
        $resolver->setAllowedTypes('pre_draw_callback', array('null', 'array'));
        $resolver->setAllowedTypes('row_callback', array('null', 'array'));
        $resolver->setAllowedTypes('state_load_callback', array('null', 'array'));
        $resolver->setAllowedTypes('state_loaded', array('null', 'array'));
        $resolver->setAllowedTypes('state_load_params', array('null', 'array'));
        $resolver->setAllowedTypes('state_save_callback', array('null', 'array'));
        $resolver->setAllowedTypes('state_save_params', array('null', 'array'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get createdRow.
     *
     * @return array|null
     */
    public function getCreatedRow()
    {
        return $this->createdRow;
    }

    /**
     * Set createdRow.
     *
     * @param array|null $createdRow
     *
     * @return $this
     */
    public function setCreatedRow($createdRow)
    {
        if (is_array($createdRow)) {
            $this->validateArrayForTemplateAndOther($createdRow);
        }

        $this->createdRow = $createdRow;

        return $this;
    }

    /**
     * Get drawCallback.
     *
     * @return array|null
     */
    public function getDrawCallback()
    {
        return $this->drawCallback;
    }

    /**
     * Set drawCallback.
     *
     * @param array|null $drawCallback
     *
     * @return $this
     */
    public function setDrawCallback($drawCallback)
    {
        if (is_array($drawCallback)) {
            $this->validateArrayForTemplateAndOther($drawCallback);
        }

        $this->drawCallback = $drawCallback;

        return $this;
    }

    /**
     * Get footerCallback.
     *
     * @return array|null
     */
    public function getFooterCallback()
    {
        return $this->footerCallback;
    }

    /**
     * Set footerCallback.
     *
     * @param array|null $footerCallback
     *
     * @return $this
     */
    public function setFooterCallback($footerCallback)
    {
        if (is_array($footerCallback)) {
            $this->validateArrayForTemplateAndOther($footerCallback);
        }

        $this->footerCallback = $footerCallback;

        return $this;
    }

    /**
     * Get formatNumber.
     *
     * @return array|null
     */
    public function getFormatNumber()
    {
        return $this->formatNumber;
    }

    /**
     * Set formatNumber.
     *
     * @param array|null $formatNumber
     *
     * @return $this
     */
    public function setFormatNumber($formatNumber)
    {
        if (is_array($formatNumber)) {
            $this->validateArrayForTemplateAndOther($formatNumber);
        }

        $this->formatNumber = $formatNumber;

        return $this;
    }

    /**
     * Get headerCallback.
     *
     * @return array|null
     */
    public function getHeaderCallback()
    {
        return $this->headerCallback;
    }

    /**
     * Set headerCallback.
     *
     * @param array|null $headerCallback
     *
     * @return $this
     */
    public function setHeaderCallback($headerCallback)
    {
        if (is_array($headerCallback)) {
            $this->validateArrayForTemplateAndOther($headerCallback);
        }

        $this->headerCallback = $headerCallback;

        return $this;
    }

    /**
     * Get infoCallback.
     *
     * @return array|null
     */
    public function getInfoCallback()
    {
        return $this->infoCallback;
    }

    /**
     * Set infoCallback.
     *
     * @param array|null $infoCallback
     *
     * @return $this
     */
    public function setInfoCallback($infoCallback)
    {
        if (is_array($infoCallback)) {
            $this->validateArrayForTemplateAndOther($infoCallback);
        }

        $this->infoCallback = $infoCallback;

        return $this;
    }

    /**
     * Get initComplete.
     *
     * @return array|null
     */
    public function getInitComplete()
    {
        return $this->initComplete;
    }

    /**
     * Set initComplete.
     *
     * @param array|null $initComplete
     *
     * @return $this
     */
    public function setInitComplete($initComplete)
    {
        if (is_array($initComplete)) {
            $this->validateArrayForTemplateAndOther($initComplete);
        }

        $this->initComplete = $initComplete;

        return $this;
    }

    /**
     * Get preDrawCallback.
     *
     * @return array|null
     */
    public function getPreDrawCallback()
    {
        return $this->preDrawCallback;
    }

    /**
     * Set preDrawCallback.
     *
     * @param array|null $preDrawCallback
     *
     * @return $this
     */
    public function setPreDrawCallback($preDrawCallback)
    {
        if (is_array($preDrawCallback)) {
            $this->validateArrayForTemplateAndOther($preDrawCallback);
        }

        $this->preDrawCallback = $preDrawCallback;

        return $this;
    }

    /**
     * Get rowCallback.
     *
     * @return array|null
     */
    public function getRowCallback()
    {
        return $this->rowCallback;
    }

    /**
     * Set rowCallback.
     *
     * @param array|null $rowCallback
     *
     * @return $this
     */
    public function setRowCallback($rowCallback)
    {
        if (is_array($rowCallback)) {
            $this->validateArrayForTemplateAndOther($rowCallback);
        }

        $this->rowCallback = $rowCallback;

        return $this;
    }

    /**
     * Get stateLoadCallback.
     *
     * @return array|null
     */
    public function getStateLoadCallback()
    {
        return $this->stateLoadCallback;
    }

    /**
     * Set stateLoadCallback.
     *
     * @param array|null $stateLoadCallback
     *
     * @return $this
     */
    public function setStateLoadCallback($stateLoadCallback)
    {
        if (is_array($stateLoadCallback)) {
            $this->validateArrayForTemplateAndOther($stateLoadCallback);
        }

        $this->stateLoadCallback = $stateLoadCallback;

        return $this;
    }

    /**
     * Get stateLoaded.
     *
     * @return array|null
     */
    public function getStateLoaded()
    {
        return $this->stateLoaded;
    }

    /**
     * Set stateLoaded.
     *
     * @param array|null $stateLoaded
     *
     * @return $this
     */
    public function setStateLoaded($stateLoaded)
    {
        if (is_array($stateLoaded)) {
            $this->validateArrayForTemplateAndOther($stateLoaded);
        }

        $this->stateLoaded = $stateLoaded;

        return $this;
    }

    /**
     * Get stateLoadParams.
     *
     * @return array|null
     */
    public function getStateLoadParams()
    {
        return $this->stateLoadParams;
    }

    /**
     * Set stateLoadParams.
     *
     * @param array|null $stateLoadParams
     *
     * @return $this
     */
    public function setStateLoadParams($stateLoadParams)
    {
        if (is_array($stateLoadParams)) {
            $this->validateArrayForTemplateAndOther($stateLoadParams);
        }

        $this->stateLoadParams = $stateLoadParams;

        return $this;
    }

    /**
     * Get stateSaveCallback.
     *
     * @return array|null
     */
    public function getStateSaveCallback()
    {
        return $this->stateSaveCallback;
    }

    /**
     * Set stateSaveCallback.
     *
     * @param array|null $stateSaveCallback
     *
     * @return $this
     */
    public function setStateSaveCallback($stateSaveCallback)
    {
        if (is_array($stateSaveCallback)) {
            $this->validateArrayForTemplateAndOther($stateSaveCallback);
        }

        $this->stateSaveCallback = $stateSaveCallback;

        return $this;
    }

    /**
     * Get stateSaveParams.
     *
     * @return array|null
     */
    public function getStateSaveParams()
    {
        return $this->stateSaveParams;
    }

    /**
     * Set stateSaveParams.
     *
     * @param array|null $stateSaveParams
     *
     * @return $this
     */
    public function setStateSaveParams($stateSaveParams)
    {
        if (is_array($stateSaveParams)) {
            $this->validateArrayForTemplateAndOther($stateSaveParams);
        }

        $this->stateSaveParams = $stateSaveParams;

        return $this;
    }
}
