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
 * Class Features
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
class Features extends AbstractViewOptions
{
    /**
     * Feature control DataTables smart column width handling.
     *
     * @var boolean
     */
    protected $autoWidth;

    /**
     * Feature control deferred rendering for additional speed of initialisation.
     *
     * @var boolean
     */
    protected $deferRender;

    /**
     * Feature control table information display field.
     *
     * @var boolean
     */
    protected $info;

    /**
     * Use markup and classes for the table to be themed by jQuery UI ThemeRoller.
     *
     * @var boolean
     * @deprecated since DataTables 1.10 (to be removed in DataTables 1.11)
     */
    protected $jQueryUi;

    /**
     * Feature control the end user's ability to change the paging display length of the table.
     *
     * @var boolean
     */
    protected $lengthChange;

    /**
     * Feature control ordering (sorting) abilities in DataTables.
     *
     * @var boolean
     */
    protected $ordering;

    /**
     * Enable or disable table pagination.
     *
     * @var boolean
     */
    protected $paging;

    /**
     * Feature control the processing indicator.
     *
     * @var boolean
     */
    protected $processing;

    /**
     * Horizontal scrolling.
     *
     * @var boolean
     */
    protected $scrollX;

    /**
     * Vertical scrolling.
     *
     * @var string
     */
    protected $scrollY;

    /**
     * Feature control search (filtering) abilities.
     *
     * @var boolean
     */
    protected $searching;

    /**
     * State saving - restore table state on page reload.
     *
     * @var boolean
     */
    protected $stateSave;

    /**
     * Delay time to render.
     *
     * @var integer
     */
    protected $delay;

    /**
     * Extensions array.
     *
     * @var array
     */
    protected $extensions;

    /**
     * Search result highlighting.
     *
     * @var boolean
     */
    protected $highlight;

    /**
     * Search result highlighting color.
     *
     * @var string
     */
    protected $highlightColor;

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'auto_width' => true,
            'defer_render' => false,
            'info' => true,
            'jquery_ui' => false,
            'length_change' => true,
            'ordering' => true,
            'paging' => true,
            'processing' => true,
            'scroll_x' => false,
            'scroll_y' => '',
            'searching' => true,
            'state_save' => false,
            'delay' => 0,
            'extensions' => array(),
            'highlight' => false,
            'highlight_color' => 'red'
        ));

        $resolver->setAllowedTypes('auto_width', 'bool');
        $resolver->setAllowedTypes('defer_render', 'bool');
        $resolver->setAllowedTypes('info', 'bool');
        $resolver->setAllowedTypes('jquery_ui', 'bool');
        $resolver->setAllowedTypes('length_change', 'bool');
        $resolver->setAllowedTypes('ordering', 'bool');
        $resolver->setAllowedTypes('paging', 'bool');
        $resolver->setAllowedTypes('processing', 'bool');
        $resolver->setAllowedTypes('scroll_x', 'bool');
        $resolver->setAllowedTypes('scroll_y', 'string');
        $resolver->setAllowedTypes('searching', 'bool');
        $resolver->setAllowedTypes('state_save', 'bool');
        $resolver->setAllowedTypes('delay', 'int');
        $resolver->setAllowedTypes('extensions', 'array');
        $resolver->setAllowedTypes('highlight', 'bool');
        $resolver->setAllowedTypes('highlight_color', 'string');

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set AutoWidth.
     *
     * @param boolean $autoWidth
     *
     * @return $this
     */
    protected function setAutoWidth($autoWidth)
    {
        $this->autoWidth = (boolean) $autoWidth;

        return $this;
    }

    /**
     * Get AutoWidth.
     *
     * @return boolean
     */
    public function getAutoWidth()
    {
        return (boolean) $this->autoWidth;
    }

    /**
     * Set DeferRender.
     *
     * @param boolean $deferRender
     *
     * @return $this
     */
    protected function setDeferRender($deferRender)
    {
        $this->deferRender = (boolean) $deferRender;

        return $this;
    }

    /**
     * Get DeferRender.
     *
     * @return boolean
     */
    public function getDeferRender()
    {
        return (boolean) $this->deferRender;
    }

    /**
     * Set Info.
     *
     * @param boolean $info
     *
     * @return $this
     */
    protected function setInfo($info)
    {
        $this->info = (boolean) $info;

        return $this;
    }

    /**
     * Get Info.
     *
     * @return boolean
     */
    public function getInfo()
    {
        return (boolean) $this->info;
    }

    /**
     * Set JqueryUi.
     *
     * @param boolean $jQueryUi
     *
     * @return $this
     * @deprecated
     */
    protected function setJqueryUi($jQueryUi)
    {
        $this->jQueryUi = (boolean) $jQueryUi;

        return $this;
    }

    /**
     * Get JqueryUi.
     *
     * @return boolean
     * @deprecated
     */
    public function getJqueryUi()
    {
        return (boolean) $this->jQueryUi;
    }

    /**
     * Set LengthChange.
     *
     * @param boolean $lengthChange
     *
     * @return $this
     */
    protected function setLengthChange($lengthChange)
    {
        $this->lengthChange = (boolean) $lengthChange;

        return $this;
    }

    /**
     * Get LengthChange.
     *
     * @return boolean
     */
    public function getLengthChange()
    {
        return (boolean) $this->lengthChange;
    }

    /**
     * Set Ordering.
     *
     * @param boolean $ordering
     *
     * @return $this
     */
    protected function setOrdering($ordering)
    {
        $this->ordering = (boolean) $ordering;

        return $this;
    }

    /**
     * Get Ordering.
     *
     * @return boolean
     */
    public function getOrdering()
    {
        return (boolean) $this->ordering;
    }

    /**
     * Set Paging.
     *
     * @param boolean $paging
     *
     * @return $this
     */
    protected function setPaging($paging)
    {
        $this->paging = (boolean) $paging;

        return $this;
    }

    /**
     * Get Paging.
     *
     * @return boolean
     */
    public function getPaging()
    {
        return (boolean) $this->paging;
    }

    /**
     * Set Processing.
     *
     * @param boolean $processing
     *
     * @return $this
     */
    protected function setProcessing($processing)
    {
        $this->processing = (boolean) $processing;

        return $this;
    }

    /**
     * Get Processing.
     *
     * @return boolean
     */
    public function getProcessing()
    {
        return (boolean) $this->processing;
    }

    /**
     * Set ScrollX.
     *
     * @param boolean $scrollX
     *
     * @return $this
     */
    protected function setScrollX($scrollX)
    {
        $this->scrollX = (boolean) $scrollX;

        return $this;
    }

    /**
     * Get ScrollX.
     *
     * @return boolean
     */
    public function getScrollX()
    {
        return (boolean) $this->scrollX;
    }

    /**
     * Set ScrollY.
     *
     * @param string $scrollY
     *
     * @return $this
     */
    protected function setScrollY($scrollY)
    {
        $this->scrollY = $scrollY;

        return $this;
    }

    /**
     * Get ScrollY
     *
     * @return string
     */
    public function getScrollY()
    {
        return $this->scrollY;
    }

    /**
     * Set Searching.
     *
     * @param boolean $searching
     *
     * @return $this
     */
    protected function setSearching($searching)
    {
        $this->searching = (boolean) $searching;

        return $this;
    }

    /**
     * Get Searching.
     *
     * @return boolean
     */
    public function getSearching()
    {
        return (boolean) $this->searching;
    }

    /**
     * Set StateSave.
     *
     * @param boolean $stateSave
     *
     * @return $this
     */
    protected function setStateSave($stateSave)
    {
        $this->stateSave = (boolean) $stateSave;

        return $this;
    }

    /**
     * Get StateSave.
     *
     * @return boolean
     */
    public function getStateSave()
    {
        return (boolean) $this->stateSave;
    }

    /**
     * Set delay.
     *
     * @param integer $delay
     *
     * @return $this
     */
    protected function setDelay($delay)
    {
        $this->delay = $delay;

        return $this;
    }

    /**
     * Get delay.
     *
     * @return integer
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * Set extensions.
     *
     * @param array $extensions
     *
     * @return $this
     */
    protected function setExtensions(array $extensions)
    {
        $this->extensions = $extensions;

        return $this;
    }

    /**
     * Get extensions.
     *
     * @return array
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * Set highlight.
     *
     * @param boolean $highlight
     *
     * @return $this
     */
    public function setHighlight($highlight)
    {
        $this->highlight = $highlight;

        return $this;
    }

    /**
     * Get highlight.
     *
     * @return boolean
     */
    public function getHighlight()
    {
        return $this->highlight;
    }

    /**
     * Set highlight color.
     *
     * @param string $highlightColor
     *
     * @return $this
     */
    public function setHighlightColor($highlightColor)
    {
        $this->highlightColor = $highlightColor;

        return $this;
    }

    /**
     * Get highlight color.
     *
     * @return string
     */
    public function getHighlightColor()
    {
        return $this->highlightColor;
    }
}
