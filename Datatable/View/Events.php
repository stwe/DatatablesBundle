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
 * Class Events
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
class Events extends AbstractViewOptions
{
    /**
     * Fired when the column widths are recalculated.
     *
     * @var array
     */
    protected $columnSizing;

    /**
     * Fired when the visibility of a column changes.
     *
     * @var array
     */
    protected $columnVisibility;

    /**
     * Fired when a table is destroyed.
     *
     * @var array
     */
    protected $destroy;

    /**
     * An error has occurred during DataTables processing of data.
     *
     * @var array
     */
    protected $error;

    /**
     * Fired when the page length is changed.
     *
     * @var array
     */
    protected $length;

    /**
     * Fired when the data contained in the table is ordered.
     *
     * @var array
     */
    protected $order;

    /**
     * Fired when the table's paging is updated.
     *
     * @var array
     */
    protected $page;

    /**
     * Triggered immediately before data load.
     *
     * @var array
     */
    protected $preInit;

    /**
     * Fired before an Ajax request is made.
     *
     * @var array
     */
    protected $preXhr;

    /**
     * Fired when DataTables is processing data.
     *
     * @var array
     */
    protected $processing;

    /**
     * Fired when the table is filtered.
     *
     * @var array
     */
    protected $search;

    /**
     * Fired once state has been loaded and applied.
     *
     * @var array
     */
    protected $stateLoaded;

    /**
     * Fired when loading state from storage.
     *
     * @var array
     */
    protected $stateLoadParams;

    /**
     * Fired when saving table state information.
     *
     * @var array
     */
    protected $stateSaveParams;

    /**
     * Fired when an Ajax request is completed.
     *
     * @var array
     */
    protected $xhr;

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'column_sizing' => array(),
            'column_visibility' => array(),
            'destroy' => array(),
            'error' => array(),
            'length' => array(),
            'order' => array(),
            'page' => array(),
            'pre_init' => array(),
            'pre_xhr' => array(),
            'processing' => array(),
            'search' => array(),
            'state_loaded' => array(),
            'state_load_params' => array(),
            'state_save_params' => array(),
            'xhr' => array(),
        ));

        $resolver->setAllowedTypes('column_sizing', 'array');
        $resolver->setAllowedTypes('column_visibility', 'array');
        $resolver->setAllowedTypes('destroy', 'array');
        $resolver->setAllowedTypes('error', 'array');
        $resolver->setAllowedTypes('length', 'array');
        $resolver->setAllowedTypes('order', 'array');
        $resolver->setAllowedTypes('page', 'array');
        $resolver->setAllowedTypes('pre_init', 'array');
        $resolver->setAllowedTypes('pre_xhr', 'array');
        $resolver->setAllowedTypes('processing', 'array');
        $resolver->setAllowedTypes('search', 'array');
        $resolver->setAllowedTypes('state_loaded', 'array');
        $resolver->setAllowedTypes('state_load_params', 'array');
        $resolver->setAllowedTypes('state_save_params', 'array');
        $resolver->setAllowedTypes('xhr', 'array');

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
     * @return array
     */
    public function getColumnSizing()
    {
        return $this->columnSizing;
    }

    /**
     * @param array $columnSizing
     *
     * @return $this
     */
    protected function setColumnSizing(array $columnSizing)
    {
        $this->columnSizing = $columnSizing;

        return $this;
    }

    /**
     * @return array
     */
    public function getColumnVisibility()
    {
        return $this->columnVisibility;
    }

    /**
     * @param array $columnVisibility
     *
     * @return $this
     */
    protected function setColumnVisibility(array $columnVisibility)
    {
        $this->columnVisibility = $columnVisibility;

        return $this;
    }

    /**
     * @return array
     */
    public function getDestroy()
    {
        return $this->destroy;
    }

    /**
     * @param array $destroy
     *
     * @return $this
     */
    protected function setDestroy(array $destroy)
    {
        $this->destroy = $destroy;

        return $this;
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param array $error
     *
     * @return $this
     */
    protected function setError(array $error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return array
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param array $length
     *
     * @return $this
     */
    protected function setLength(array $length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return array
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param array $order
     *
     * @return $this
     */
    protected function setOrder(array $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return array
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param array $page
     *
     * @return $this
     */
    protected function setPage(array $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return array
     */
    public function getPreInit()
    {
        return $this->preInit;
    }

    /**
     * @param array $preInit
     *
     * @return $this
     */
    protected function setPreInit(array $preInit)
    {
        $this->preInit = $preInit;

        return $this;
    }

    /**
     * @return array
     */
    public function getPreXhr()
    {
        return $this->preXhr;
    }

    /**
     * @param array $preXhr
     *
     * @return $this
     */
    protected function setPreXhr(array $preXhr)
    {
        $this->preXhr = $preXhr;

        return $this;
    }

    /**
     * @return array
     */
    public function getProcessing()
    {
        return $this->processing;
    }

    /**
     * @param array $processing
     *
     * @return $this
     */
    protected function setProcessing(array $processing)
    {
        $this->processing = $processing;

        return $this;
    }

    /**
     * @return array
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param array $search
     *
     * @return $this
     */
    protected function setSearch(array $search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * @return array
     */
    public function getStateLoaded()
    {
        return $this->stateLoaded;
    }

    /**
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
     * @return array
     */
    public function getStateLoadParams()
    {
        return $this->stateLoadParams;
    }

    /**
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
     * @return array
     */
    public function getStateSaveParams()
    {
        return $this->stateSaveParams;
    }

    /**
     * @param array $stateSaveParams
     *
     * @return $this
     */
    protected function setStateSaveParams(array $stateSaveParams)
    {
        $this->stateSaveParams = $stateSaveParams;

        return $this;
    }

    /**
     * @return array
     */
    public function getXhr()
    {
        return $this->xhr;
    }

    /**
     * @param array $xhr
     *
     * @return $this
     */
    protected function setXhr(array $xhr)
    {
        $this->xhr = $xhr;

        return $this;
    }
}
