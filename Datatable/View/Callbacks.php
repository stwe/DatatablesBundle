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
use Symfony\Component\DependencyInjection\Container;
use Exception;

/**
 * Class Options
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
class Callbacks
{
    /**
     * Callbacks container.
     *
     * @var array
     */
    protected $callbacks;

    /**
     * An OptionsResolver instance.
     *
     * @var OptionsResolver
     */
    protected $resolver;


    /**
     * Callback for whenever a TR element is created for the table's body.
     *
     * @var string
     */
    protected $createdRow;

    /**
     * Function that is called every time DataTables performs a draw.
     *
     * @var string
     */
    protected $drawCallback;

    /**
     * Footer display callback function.
     *
     * @var string
     */
    protected $footerCallback;

    /**
     * Number formatting callback function.
     *
     * @var string
     */
    protected $formatNumber;

    /**
     * Header display callback function.
     *
     * @var string
     */
    protected $headerCallback;

    /**
     * Table summary information display callback.
     *
     * @var string
     */
    protected $infoCallback;

    /**
     * Initialisation complete callback.
     *
     * @var string
     */
    protected $initComplete;

    /**
     * Pre-draw callback.
     *
     * @var string
     */
    protected $preDrawCallback;

    /**
     * Row draw callback.
     *
     * @var string
     */
    protected $rowCallback;

    /**
     * Callback that defines where and how a saved state should be loaded.
     *
     * @var string
     */
    protected $stateLoadCallback;

    /**
     * State loaded callback.
     *
     * @var string
     */
    protected $stateLoaded;

    /**
     * State loaded - data manipulation callback
     *
     * @var string
     */
    protected $stateLoadParams;

    /**
     * Callback that defines how the table state is stored and where.
     *
     * @var string
     */
    protected $stateSaveCallback;

    /**
     * State save - data manipulation callback
     *
     * @var string
     */
    protected $stateSaveParams;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->callbacks = array();
        $this->resolver = new OptionsResolver();
        $this->configureOptions($this->resolver);
        $this->setCallbacks($this->callbacks);
    }

    //-------------------------------------------------
    // Setup Callbacks
    //-------------------------------------------------

    /**
     * Set callbacks.
     *
     * @param array $callbacks
     *
     * @return $this
     */
    public function setCallbacks(array $callbacks)
    {
        $this->callbacks = $this->resolver->resolve($callbacks);
        $this->callingSettersWithOptions($this->callbacks);

        return $this;
    }

    /**
     * Configure Options.
     *
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "created_row" => "",
            "draw_callback" => "",
            "footer_callback" => "",
            "format_number" => "",
            "header_callback" => "",
            "info_callback" => "",
            "init_complete" => "",
            "pre_draw_callback" => "",
            "row_callback" => "",
            "state_load_callback" => "",
            "state_loaded" => "",
            "state_load_params" => "",
            "state_save_callback" => "",
            "state_save_params" => "",
        ));

        $resolver->setAllowedTypes(array(
            "created_row" => "string",
            "draw_callback" => "string",
            "footer_callback" => "string",
            "format_number" => "string",
            "header_callback" => "string",
            "info_callback" => "string",
            "init_complete" => "string",
            "pre_draw_callback" => "string",
            "row_callback" => "string",
            "state_load_callback" => "string",
            "state_loaded" => "string",
            "state_load_params" => "string",
            "state_save_callback" => "string",
            "state_save_params" => "string",
        ));

        return $this;
    }

    /**
     * Calling setters with options.
     *
     * @param array $options
     *
     * @return $this
     * @throws Exception
     */
    private function callingSettersWithOptions(array $options)
    {
        $methods = get_class_methods($this);

        foreach ($options as $key => $value) {
            $key = Container::camelize($key);
            $method = "set" . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            } else {
                throw new Exception("callingSettersWithOptions(): {$method} invalid method name");
            }
        }

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get CreatedRow.
     *
     * @return string
     */
    public function getCreatedRow()
    {
        return (string) $this->createdRow;
    }

    /**
     * Set CreatedRow.
     *
     * @param string $createdRow
     *
     * @return $this
     */
    public function setCreatedRow($createdRow)
    {
        $this->createdRow = $createdRow;

        return $this;
    }

    /**
     * Get DrawCallback.
     *
     * @return string
     */
    public function getDrawCallback()
    {
        return (string) $this->drawCallback;
    }

    /**
     * Set DrawCallback.
     *
     * @param string $drawCallback
     *
     * @return $this
     */
    public function setDrawCallback($drawCallback)
    {
        $this->drawCallback = $drawCallback;
        return $this;
    }

    /**
     * Get FooterCallback.
     *
     * @return string
     */
    public function getFooterCallback()
    {
        return (string) $this->footerCallback;
    }

    /**
     * Set FooterCallback.
     *
     * @param string $footerCallback
     *
     * @return $this
     */
    public function setFooterCallback($footerCallback)
    {
        $this->footerCallback = $footerCallback;

        return $this;
    }

    /**
     * Get FormatNumber.
     *
     * @return string
     */
    public function getFormatNumber()
    {
        return (string) $this->formatNumber;
    }

    /**
     * Set FormatNumber.
     *
     * @param string $formatNumber
     *
     * @return $this
     */
    public function setFormatNumber($formatNumber)
    {
        $this->formatNumber = $formatNumber;

        return $this;
    }

    /**
     * Get HeaderCallback.
     *
     * @return string
     */
    public function getHeaderCallback()
    {
        return (string) $this->headerCallback;
    }

    /**
     * Set HeaderCallback.
     *
     * @param string $headerCallback
     *
     * @return $this
     */
    public function setHeaderCallback($headerCallback)
    {
        $this->headerCallback = $headerCallback;

        return $this;
    }

    /**
     * Get InfoCallback.
     *
     * @return string
     */
    public function getInfoCallback()
    {
        return (string) $this->infoCallback;
    }

    /**
     * Set InfoCallback.
     *
     * @param string $infoCallback
     *
     * @return $this
     */
    public function setInfoCallback($infoCallback)
    {
        $this->infoCallback = $infoCallback;

        return $this;
    }

    /**
     * Get InitComplete.
     *
     * @return string
     */
    public function getInitComplete()
    {
        return (string) $this->initComplete;
    }

    /**
     * Set InitComplete.
     *
     * @param string $initComplete
     *
     * @return $this
     */
    public function setInitComplete($initComplete)
    {
        $this->initComplete = $initComplete;

        return $this;
    }

    /**
     * Get PreDrawCallback.
     *
     * @return string
     */
    public function getPreDrawCallback()
    {
        return (string) $this->preDrawCallback;
    }

    /**
     * Set PreDrawCallback.
     *
     * @param string $preDrawCallback
     *
     * @return $this
     */
    public function setPreDrawCallback($preDrawCallback)
    {
        $this->preDrawCallback = $preDrawCallback;

        return $this;
    }

    /**
     * Get RowCallback.
     *
     * @return string
     */
    public function getRowCallback()
    {
        return (string) $this->rowCallback;
    }

    /**
     * Set RowCallback.
     *
     * @param string $rowCallback
     *
     * @return $this
     */
    public function setRowCallback($rowCallback)
    {
        $this->rowCallback = $rowCallback;

        return $this;
    }

    /**
     * Get StateLoadCallback.
     *
     * @return string
     */
    public function getStateLoadCallback()
    {
        return (string) $this->stateLoadCallback;
    }

    /**
     * Set StateLoadCallback.
     *
     * @param string $stateLoadCallback
     *
     * @return $this
     */
    public function setStateLoadCallback($stateLoadCallback)
    {
        $this->stateLoadCallback = $stateLoadCallback;

        return $this;
    }

    /**
     * Get StateLoaded.
     *
     * @return string
     */
    public function getStateLoaded()
    {
        return (string) $this->stateLoaded;
    }

    /**
     * Set StateLoaded.
     *
     * @param string $stateLoaded
     *
     * @return $this
     */
    public function setStateLoaded($stateLoaded)
    {
        $this->stateLoaded = $stateLoaded;

        return $this;
    }

    /**
     * Get StateLoadParams.
     *
     * @return string
     */
    public function getStateLoadParams()
    {
        return (string) $this->stateLoadParams;
    }

    /**
     * Set StateLoadParams.
     *
     * @param string $stateLoadParams
     *
     * @return $this
     */
    public function setStateLoadParams($stateLoadParams)
    {
        $this->stateLoadParams = $stateLoadParams;

        return $this;
    }

    /**
     * Get StateSaveCallback.
     *
     * @return string
     */
    public function getStateSaveCallback()
    {
        return (string) $this->stateSaveCallback;
    }

    /**
     * Set StateSaveCallback.
     *
     * @param string $stateSaveCallback
     *
     * @return $this
     */
    public function setStateSaveCallback($stateSaveCallback)
    {
        $this->stateSaveCallback = $stateSaveCallback;

        return $this;
    }

    /**
     * Get StateSaveParams.
     *
     * @return string
     */
    public function getStateSaveParams()
    {
        return (string) $this->stateSaveParams;
    }

    /**
     * Set StateSaveParams.
     *
     * @param string $stateSaveParams
     *
     * @return $this
     */
    public function setStateSaveParams($stateSaveParams)
    {
        $this->stateSaveParams = $stateSaveParams;

        return $this;
    }
}
