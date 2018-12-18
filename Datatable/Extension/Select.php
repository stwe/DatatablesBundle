<?php

/**
 * This file is part of the SgDatatablesBundle package.
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Extension;

use Sg\DatatablesBundle\Datatable\OptionsTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Select
 *
 * @package Sg\DatatablesBundle\Datatable\Extension
 */
class Select {
    /**
     * Use the OptionsResolver.
     */
    use OptionsTrait;

    //-------------------------------------------------
    // DataTables - Select Extension - select
    //-------------------------------------------------

    /**
     * Indicate if the selected items will be removed when clicking outside of the table
     *
     * @var boolean|null
     */
    protected $blurable;

    /**
     * Set the class name that will be applied to selected items
     *
     * @var string|null
     */
    protected $className;

    /**
     * Enable / disable the display for item selection information in the table summary
     *
     * @var boolean|null
     */
    protected $info;

    /**
     * Set which table items to select (rows, columns or cells)
     *
     * @var string|null
     */
    protected $items;

    /**
     * Set the element selector used for mouse event capture to select items
     *
     * @var string|null
     */
    protected $selector;

    /**
     * Set the selection style for end user interaction with the table
     *
     * @var string|null
     */
    protected $style;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Select constructor.
     */
    public function __construct() {
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
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(
            array(
                'blurable'   => null,
                'class_name' => null,
                'info'       => null,
                'items'      => null,
                'selector'   => null,
                'style'      => null,
            )
        );

        $resolver->setAllowedTypes('blurable', array('boolean', 'null'));
        $resolver->setAllowedTypes('class_name', array('string', 'null'));
        $resolver->setAllowedTypes('info', array('boolean', 'null'));
        $resolver->setAllowedTypes('items', array('string', 'null'));
        $resolver->setAllowedValues('items', array('row', 'column', 'cell'));
        $resolver->setAllowedTypes('selector', array('string', 'null'));
        $resolver->setAllowedTypes('style', array('string', 'null'));
        $resolver->setAllowedValues('style', array('api', 'single', 'multi', 'os', 'multi+shift'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get blurable.
     *
     * @return boolean|null
     */
    public function getBlurable() {
        return $this->blurable;
    }

    /**
     * Set blurable.
     *
     * @param string|null $blurable
     *
     * @return $this
     */
    public function setBlurable($blurable) {
        $this->blurable = $blurable;

        return $this;
    }

    /**
     * Get className.
     *
     * @return null|string
     */
    public function getClassName() {
        return $this->className;
    }

    /**
     * Set className.
     *
     * @param null|string $className
     *
     * @return $this
     */
    public function setClassName($className) {
        $this->className = $className;

        return $this;
    }

    /**
     * Get info.
     *
     * @return boolean|null
     */
    public function getInfo() {
        return $this->info;
    }

    /**
     * Set info.
     *
     * @param boolean|null $info
     *
     * @return $this
     */
    public function setInfo($info) {
        $this->info = $info;

        return $this;
    }

    /**
     * Get items.
     *
     * @return string|null
     */
    public function getItems() {
        return $this->items;
    }

    /**
     * Set items.
     *
     * @param string|null $items
     *
     * @return $this
     */
    public function setItems($items) {
        $this->items = $items;

        return $this;
    }

    /**
     * Get selector.
     *
     * @return string|null
     */
    public function getSelector() {
        return $this->selector;
    }

    /**
     * Set selector.
     *
     * @param string|null $selector
     *
     * @return $this
     */
    public function setSelector($selector) {
        $this->selector = $selector;

        return $this;
    }

    /**
     * Get style.
     *
     * @return string|null
     */
    public function getStyle() {
        return $this->style;
    }

    /**
     * Set style.
     *
     * @param string|null $style
     *
     * @return $this
     */
    public function setStyle($style) {
        $this->style = $style;

        return $this;
    }
}
