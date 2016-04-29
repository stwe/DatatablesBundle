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
     * @var string
     */
    protected $columnSizing;

    /**
     * Fired when the visibility of a column changes.
     *
     * @var string
     */
    protected $columnVisibility;

    /**
     * Fired when a table is destroyed.
     *
     * @var string
     */
    protected $destroy;

    /**
     * Fired once the table has completed a draw.
     *
     * @var string
     */
    protected $draw;

    /**
     * An error has occurred during DataTables processing of data.
     *
     * @var string
     */
    protected $error;

    /**
     * Fired when DataTables has been fully initialised and data loaded.
     *
     * @var string
     */
    protected $init;

    /**
     * Fired when the page length is changed.
     *
     * @var string
     */
    protected $length;

    /**
     * Fired when the data contained in the table is ordered.
     *
     * @var string
     */
    protected $order;

    /**
     * Fired when the table's paging is updated.
     *
     * @var string
     */
    protected $page;

    /**
     * Triggered immediately before data load.
     *
     * @var string
     */
    protected $preInit;

    /**
     * Fired before an Ajax request is made.
     *
     * @var string
     */
    protected $preXhr;

    /**
     * Fired when DataTables is processing data.
     *
     * @var string
     */
    protected $processing;

    /**
     * Fired when the table is filtered.
     *
     * @var string
     */
    protected $search;

    /**
     * Fired once state has been loaded and applied.
     *
     * @var string
     */
    protected $stateLoaded;

    /**
     * Fired when loading state from storage.
     *
     * @var string
     */
    protected $stateLoadParams;

    /**
     * Fired when saving table state information.
     *
     * @var string
     */
    protected $stateSaveParams;

    /**
     * Fired when an Ajax request is completed.
     *
     * @var string
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
            'column_sizing' => '',
            'column_visibility' => '',
            'destroy' => '',
            //'draw' => '',
            'error' => '',
            //'init' => '',
            'length' => '',
            'order' => '',
            'page' => '',
            'pre_init' => '',
            'pre_xhr' => '',
            'processing' => '',
            'search' => '',
            'state_loaded' => '',
            'state_load_params' => '',
            'state_save_params' => '',
            'xhr' => '',
        ));

        $resolver->setAllowedTypes('column_sizing', 'string');
        $resolver->setAllowedTypes('column_visibility', 'string');
        $resolver->setAllowedTypes('destroy', 'string');
        //$resolver->setAllowedTypes('draw', 'string');
        $resolver->setAllowedTypes('error', 'string');
        //$resolver->setAllowedTypes('init', 'string');
        $resolver->setAllowedTypes('length', 'string');
        $resolver->setAllowedTypes('order', 'string');
        $resolver->setAllowedTypes('page', 'string');
        $resolver->setAllowedTypes('pre_init', 'string');
        $resolver->setAllowedTypes('pre_xhr', 'string');
        $resolver->setAllowedTypes('processing', 'string');
        $resolver->setAllowedTypes('search', 'string');
        $resolver->setAllowedTypes('state_loaded', 'string');
        $resolver->setAllowedTypes('state_load_params', 'string');
        $resolver->setAllowedTypes('state_save_params', 'string');
        $resolver->setAllowedTypes('xhr', 'string');

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return string
     */
    public function getColumnSizing()
    {
        return $this->columnSizing;
    }

    /**
     * @param string $columnSizing
     *
     * @return $this
     */
    protected function setColumnSizing($columnSizing)
    {
        $this->columnSizing = $columnSizing;

        return $this;
    }

    /**
     * @return string
     */
    public function getColumnVisibility()
    {
        return $this->columnVisibility;
    }

    /**
     * @param string $columnVisibility
     *
     * @return $this
     */
    protected function setColumnVisibility($columnVisibility)
    {
        $this->columnVisibility = $columnVisibility;

        return $this;
    }

    /**
     * @return string
     */
    public function getDestroy()
    {
        return $this->destroy;
    }

    /**
     * @param string $destroy
     *
     * @return $this
     */
    protected function setDestroy($destroy)
    {
        $this->destroy = $destroy;

        return $this;
    }

    /*
    public function getDraw()
    {
        return $this->draw;
    }

    protected function setDraw($draw)
    {
        $this->draw = $draw;

        return $this;
    }
    */

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     *
     * @return $this
     */
    protected function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /*
    public function getInit()
    {
        return $this->init;
    }

    protected function setInit($init)
    {
        $this->init = $init;

        return $this;
    }
    */

    /**
     * @return string
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param string $length
     *
     * @return $this
     */
    protected function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param string $order
     *
     * @return $this
     */
    protected function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return string
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param string $page
     *
     * @return $this
     */
    protected function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return string
     */
    public function getPreInit()
    {
        return $this->preInit;
    }

    /**
     * @param string $preInit
     *
     * @return $this
     */
    protected function setPreInit($preInit)
    {
        $this->preInit = $preInit;

        return $this;
    }

    /**
     * @return string
     */
    public function getPreXhr()
    {
        return $this->preXhr;
    }

    /**
     * @param string $preXhr
     *
     * @return $this
     */
    protected function setPreXhr($preXhr)
    {
        $this->preXhr = $preXhr;

        return $this;
    }

    /**
     * @return string
     */
    public function getProcessing()
    {
        return $this->processing;
    }

    /**
     * @param string $processing
     *
     * @return $this
     */
    protected function setProcessing($processing)
    {
        $this->processing = $processing;

        return $this;
    }

    /**
     * @return string
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param string $search
     *
     * @return $this
     */
    protected function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * @return string
     */
    public function getStateLoaded()
    {
        return $this->stateLoaded;
    }

    /**
     * @param string $stateLoaded
     *
     * @return $this
     */
    protected function setStateLoaded($stateLoaded)
    {
        $this->stateLoaded = $stateLoaded;

        return $this;
    }

    /**
     * @return string
     */
    public function getStateLoadParams()
    {
        return $this->stateLoadParams;
    }

    /**
     * @param string $stateLoadParams
     *
     * @return $this
     */
    protected function setStateLoadParams($stateLoadParams)
    {
        $this->stateLoadParams = $stateLoadParams;

        return $this;
    }

    /**
     * @return string
     */
    public function getStateSaveParams()
    {
        return $this->stateSaveParams;
    }

    /**
     * @param string $stateSaveParams
     *
     * @return $this
     */
    protected function setStateSaveParams($stateSaveParams)
    {
        $this->stateSaveParams = $stateSaveParams;

        return $this;
    }

    /**
     * @return string
     */
    public function getXhr()
    {
        return $this->xhr;
    }

    /**
     * @param string $xhr
     *
     * @return $this
     */
    protected function setXhr($xhr)
    {
        $this->xhr = $xhr;

        return $this;
    }
}
