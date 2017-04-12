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
 * Class Events
 *
 * @package Sg\DatatablesBundle\Datatable
 */
class Events
{
    /**
     * Use the OptionsResolver.
     */
    use OptionsTrait;

    //-------------------------------------------------
    // DataTables - Events
    // -------------------
    // The "draw" event is already used by this bundle.
    // The "init" event is reserved.
    //-------------------------------------------------

    /**
     * Fired when the column widths are recalculated.
     *
     * @var null|array
     */
    protected $columnSizing;

    /**
     * Fired when the visibility of a column changes.
     *
     * @var null|array
     */
    protected $columnVisibility;

    /**
     * Fired when a table is destroyed.
     *
     * @var null|array
     */
    protected $destroy;

    /**
     * An error has occurred during DataTables processing of data.
     *
     * @var null|array
     */
    protected $error;

    /**
     * Fired when the page length is changed.
     *
     * @var null|array
     */
    protected $length;

    /**
     * Fired when the data contained in the table is ordered.
     *
     * @var null|array
     */
    protected $order;

    /**
     * Fired when the table's paging is updated.
     *
     * @var null|array
     */
    protected $page;

    /**
     * Triggered immediately before data load.
     *
     * @var null|array
     */
    protected $preInit;

    /**
     * Fired before an Ajax request is made.
     *
     * @var null|array
     */
    protected $preXhr;

    /**
     * Fired when DataTables is processing data.
     *
     * @var null|array
     */
    protected $processing;

    /**
     * Fired when the table is filtered.
     *
     * @var null|array
     */
    protected $search;

    /**
     * Fired once state has been loaded and applied.
     *
     * @var null|array
     */
    protected $stateLoaded;

    /**
     * Fired when loading state from storage.
     *
     * @var null|array
     */
    protected $stateLoadParams;

    /**
     * Fired when saving table state information.
     *
     * @var null|array
     */
    protected $stateSaveParams;

    /**
     * Fired when an Ajax request is completed.
     *
     * @var null|array
     */
    protected $xhr;

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
            'column_sizing' => null,
            'column_visibility' => null,
            'destroy' => null,
            'error' => null,
            'length' => null,
            'order' => null,
            'page' => null,
            'pre_init' => null,
            'pre_xhr' => null,
            'processing' => null,
            'search' => null,
            'state_loaded' => null,
            'state_load_params' => null,
            'state_save_params' => null,
            'xhr' => null,
        ));

        $resolver->setAllowedTypes('column_sizing', array('null', 'array'));
        $resolver->setAllowedTypes('column_visibility', array('null', 'array'));
        $resolver->setAllowedTypes('destroy', array('null', 'array'));
        $resolver->setAllowedTypes('error', array('null', 'array'));
        $resolver->setAllowedTypes('length', array('null', 'array'));
        $resolver->setAllowedTypes('order', array('null', 'array'));
        $resolver->setAllowedTypes('page', array('null', 'array'));
        $resolver->setAllowedTypes('pre_init', array('null', 'array'));
        $resolver->setAllowedTypes('pre_xhr', array('null', 'array'));
        $resolver->setAllowedTypes('processing', array('null', 'array'));
        $resolver->setAllowedTypes('search', array('null', 'array'));
        $resolver->setAllowedTypes('state_loaded', array('null', 'array'));
        $resolver->setAllowedTypes('state_load_params', array('null', 'array'));
        $resolver->setAllowedTypes('state_save_params', array('null', 'array'));
        $resolver->setAllowedTypes('xhr', array('null', 'array'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get columnSizing.
     *
     * @return array|null
     */
    public function getColumnSizing()
    {
        return $this->columnSizing;
    }

    /**
     * Set columnSizing.
     *
     * @param array|null $columnSizing
     *
     * @return $this
     */
    public function setColumnSizing($columnSizing)
    {
        if (is_array($columnSizing)) {
            $this->validateArrayForTemplateAndOther($columnSizing);
        }

        $this->columnSizing = $columnSizing;

        return $this;
    }

    /**
     * Get columnVisibility.
     *
     * @return array|null
     */
    public function getColumnVisibility()
    {
        return $this->columnVisibility;
    }

    /**
     * Set columnVisibility.
     *
     * @param array|null $columnVisibility
     *
     * @return $this
     */
    public function setColumnVisibility($columnVisibility)
    {
        if (is_array($columnVisibility)) {
            $this->validateArrayForTemplateAndOther($columnVisibility);
        }

        $this->columnVisibility = $columnVisibility;

        return $this;
    }

    /**
     * Get destroy.
     *
     * @return array|null
     */
    public function getDestroy()
    {
        return $this->destroy;
    }

    /**
     * Set destroy.
     *
     * @param array|null $destroy
     *
     * @return $this
     */
    public function setDestroy($destroy)
    {
        if (is_array($destroy)) {
            $this->validateArrayForTemplateAndOther($destroy);
        }

        $this->destroy = $destroy;

        return $this;
    }

    /**
     * Get error.
     *
     * @return array|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set error.
     *
     * @param array|null $error
     *
     * @return $this
     */
    public function setError($error)
    {
        if (is_array($error)) {
            $this->validateArrayForTemplateAndOther($error);
        }

        $this->error = $error;

        return $this;
    }

    /**
     * Get length.
     *
     * @return array|null
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set length.
     *
     * @param array|null $length
     *
     * @return $this
     */
    public function setLength($length)
    {
        if (is_array($length)) {
            $this->validateArrayForTemplateAndOther($length);
        }

        $this->length = $length;

        return $this;
    }

    /**
     * Get order.
     *
     * @return array|null
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set order.
     *
     * @param array|null $order
     *
     * @return $this
     */
    public function setOrder($order)
    {
        if (is_array($order)) {
            $this->validateArrayForTemplateAndOther($order);
        }

        $this->order = $order;

        return $this;
    }

    /**
     * Get page.
     *
     * @return array|null
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set page.
     *
     * @param array|null $page
     *
     * @return $this
     */
    public function setPage($page)
    {
        if (is_array($page)) {
            $this->validateArrayForTemplateAndOther($page);
        }

        $this->page = $page;

        return $this;
    }

    /**
     * Get preInit.
     *
     * @return array|null
     */
    public function getPreInit()
    {
        return $this->preInit;
    }

    /**
     * Set preInit.
     *
     * @param array|null $preInit
     *
     * @return $this
     */
    public function setPreInit($preInit)
    {
        if (is_array($preInit)) {
            $this->validateArrayForTemplateAndOther($preInit);
        }

        $this->preInit = $preInit;

        return $this;
    }

    /**
     * Get preXhr.
     *
     * @return array|null
     */
    public function getPreXhr()
    {
        return $this->preXhr;
    }

    /**
     * Set preXhr.
     *
     * @param array|null $preXhr
     *
     * @return $this
     */
    public function setPreXhr($preXhr)
    {
        if (is_array($preXhr)) {
            $this->validateArrayForTemplateAndOther($preXhr);
        }

        $this->preXhr = $preXhr;

        return $this;
    }

    /**
     * Get processing.
     *
     * @return array|null
     */
    public function getProcessing()
    {
        return $this->processing;
    }

    /**
     * Set processing.
     *
     * @param array|null $processing
     *
     * @return $this
     */
    public function setProcessing($processing)
    {
        if (is_array($processing)) {
            $this->validateArrayForTemplateAndOther($processing);
        }

        $this->processing = $processing;

        return $this;
    }

    /**
     * Get search.
     *
     * @return array|null
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Set search.
     *
     * @param array|null $search
     *
     * @return $this
     */
    public function setSearch($search)
    {
        if (is_array($search)) {
            $this->validateArrayForTemplateAndOther($search);
        }

        $this->search = $search;

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

    /**
     * Get xhr.
     *
     * @return array|null
     */
    public function getXhr()
    {
        return $this->xhr;
    }

    /**
     * Set xhr.
     *
     * @param array|null $xhr
     *
     * @return $this
     */
    public function setXhr($xhr)
    {
        if (is_array($xhr)) {
            $this->validateArrayForTemplateAndOther($xhr);
        }

        $this->xhr = $xhr;

        return $this;
    }
}
