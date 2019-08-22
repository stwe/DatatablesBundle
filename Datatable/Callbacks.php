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

class Callbacks
{
    use OptionsTrait;

    //-------------------------------------------------
    // DataTables - Callbacks
    //-------------------------------------------------

    /**
     * Callback for whenever a TR element is created for the table's body.
     *
     * @var array|null
     */
    protected $createdRow;

    /**
     * Function that is called every time DataTables performs a draw.
     *
     * @var array|null
     */
    protected $drawCallback;

    /**
     * Footer display callback function.
     *
     * @var array|null
     */
    protected $footerCallback;

    /**
     * Number formatting callback function.
     *
     * @var array|null
     */
    protected $formatNumber;

    /**
     * Header display callback function.
     *
     * @var array|null
     */
    protected $headerCallback;

    /**
     * Table summary information display callback.
     *
     * @var array|null
     */
    protected $infoCallback;

    /**
     * Initialisation complete callback.
     *
     * @var array|null
     */
    protected $initComplete;

    /**
     * Pre-draw callback.
     *
     * @var array|null
     */
    protected $preDrawCallback;

    /**
     * Row draw callback.
     *
     * @var array|null
     */
    protected $rowCallback;

    /**
     * Callback that defines where and how a saved state should be loaded.
     *
     * @var array|null
     */
    protected $stateLoadCallback;

    /**
     * State loaded callback.
     *
     * @var array|null
     */
    protected $stateLoaded;

    /**
     * State loaded - data manipulation callback.
     *
     * @var array|null
     */
    protected $stateLoadParams;

    /**
     * Callback that defines how the table state is stored and where.
     *
     * @var array|null
     */
    protected $stateSaveCallback;

    /**
     * State save - data manipulation callback.
     *
     * @var array|null
     */
    protected $stateSaveParams;

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
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
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
        ]);

        $resolver->setAllowedTypes('created_row', ['null', 'array']);
        $resolver->setAllowedTypes('draw_callback', ['null', 'array']);
        $resolver->setAllowedTypes('footer_callback', ['null', 'array']);
        $resolver->setAllowedTypes('format_number', ['null', 'array']);
        $resolver->setAllowedTypes('header_callback', ['null', 'array']);
        $resolver->setAllowedTypes('info_callback', ['null', 'array']);
        $resolver->setAllowedTypes('init_complete', ['null', 'array']);
        $resolver->setAllowedTypes('pre_draw_callback', ['null', 'array']);
        $resolver->setAllowedTypes('row_callback', ['null', 'array']);
        $resolver->setAllowedTypes('state_load_callback', ['null', 'array']);
        $resolver->setAllowedTypes('state_loaded', ['null', 'array']);
        $resolver->setAllowedTypes('state_load_params', ['null', 'array']);
        $resolver->setAllowedTypes('state_save_callback', ['null', 'array']);
        $resolver->setAllowedTypes('state_save_params', ['null', 'array']);

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return array|null
     */
    public function getCreatedRow()
    {
        return $this->createdRow;
    }

    /**
     * @param array|null $createdRow
     *
     * @return $this
     */
    public function setCreatedRow($createdRow)
    {
        if (\is_array($createdRow)) {
            $this->validateArrayForTemplateAndOther($createdRow);
        }

        $this->createdRow = $createdRow;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getDrawCallback()
    {
        return $this->drawCallback;
    }

    /**
     * @param array|null $drawCallback
     *
     * @return $this
     */
    public function setDrawCallback($drawCallback)
    {
        if (\is_array($drawCallback)) {
            $this->validateArrayForTemplateAndOther($drawCallback);
        }

        $this->drawCallback = $drawCallback;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getFooterCallback()
    {
        return $this->footerCallback;
    }

    /**
     * @param array|null $footerCallback
     *
     * @return $this
     */
    public function setFooterCallback($footerCallback)
    {
        if (\is_array($footerCallback)) {
            $this->validateArrayForTemplateAndOther($footerCallback);
        }

        $this->footerCallback = $footerCallback;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getFormatNumber()
    {
        return $this->formatNumber;
    }

    /**
     * @param array|null $formatNumber
     *
     * @return $this
     */
    public function setFormatNumber($formatNumber)
    {
        if (\is_array($formatNumber)) {
            $this->validateArrayForTemplateAndOther($formatNumber);
        }

        $this->formatNumber = $formatNumber;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getHeaderCallback()
    {
        return $this->headerCallback;
    }

    /**
     * @param array|null $headerCallback
     *
     * @return $this
     */
    public function setHeaderCallback($headerCallback)
    {
        if (\is_array($headerCallback)) {
            $this->validateArrayForTemplateAndOther($headerCallback);
        }

        $this->headerCallback = $headerCallback;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getInfoCallback()
    {
        return $this->infoCallback;
    }

    /**
     * @param array|null $infoCallback
     *
     * @return $this
     */
    public function setInfoCallback($infoCallback)
    {
        if (\is_array($infoCallback)) {
            $this->validateArrayForTemplateAndOther($infoCallback);
        }

        $this->infoCallback = $infoCallback;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getInitComplete()
    {
        return $this->initComplete;
    }

    /**
     * @param array|null $initComplete
     *
     * @return $this
     */
    public function setInitComplete($initComplete)
    {
        if (\is_array($initComplete)) {
            $this->validateArrayForTemplateAndOther($initComplete);
        }

        $this->initComplete = $initComplete;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getPreDrawCallback()
    {
        return $this->preDrawCallback;
    }

    /**
     * @param array|null $preDrawCallback
     *
     * @return $this
     */
    public function setPreDrawCallback($preDrawCallback)
    {
        if (\is_array($preDrawCallback)) {
            $this->validateArrayForTemplateAndOther($preDrawCallback);
        }

        $this->preDrawCallback = $preDrawCallback;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getRowCallback()
    {
        return $this->rowCallback;
    }

    /**
     * @param array|null $rowCallback
     *
     * @return $this
     */
    public function setRowCallback($rowCallback)
    {
        if (\is_array($rowCallback)) {
            $this->validateArrayForTemplateAndOther($rowCallback);
        }

        $this->rowCallback = $rowCallback;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getStateLoadCallback()
    {
        return $this->stateLoadCallback;
    }

    /**
     * @param array|null $stateLoadCallback
     *
     * @return $this
     */
    public function setStateLoadCallback($stateLoadCallback)
    {
        if (\is_array($stateLoadCallback)) {
            $this->validateArrayForTemplateAndOther($stateLoadCallback);
        }

        $this->stateLoadCallback = $stateLoadCallback;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getStateLoaded()
    {
        return $this->stateLoaded;
    }

    /**
     * @param array|null $stateLoaded
     *
     * @return $this
     */
    public function setStateLoaded($stateLoaded)
    {
        if (\is_array($stateLoaded)) {
            $this->validateArrayForTemplateAndOther($stateLoaded);
        }

        $this->stateLoaded = $stateLoaded;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getStateLoadParams()
    {
        return $this->stateLoadParams;
    }

    /**
     * @param array|null $stateLoadParams
     *
     * @return $this
     */
    public function setStateLoadParams($stateLoadParams)
    {
        if (\is_array($stateLoadParams)) {
            $this->validateArrayForTemplateAndOther($stateLoadParams);
        }

        $this->stateLoadParams = $stateLoadParams;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getStateSaveCallback()
    {
        return $this->stateSaveCallback;
    }

    /**
     * @param array|null $stateSaveCallback
     *
     * @return $this
     */
    public function setStateSaveCallback($stateSaveCallback)
    {
        if (\is_array($stateSaveCallback)) {
            $this->validateArrayForTemplateAndOther($stateSaveCallback);
        }

        $this->stateSaveCallback = $stateSaveCallback;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getStateSaveParams()
    {
        return $this->stateSaveParams;
    }

    /**
     * @param array|null $stateSaveParams
     *
     * @return $this
     */
    public function setStateSaveParams($stateSaveParams)
    {
        if (\is_array($stateSaveParams)) {
            $this->validateArrayForTemplateAndOther($stateSaveParams);
        }

        $this->stateSaveParams = $stateSaveParams;

        return $this;
    }
}
