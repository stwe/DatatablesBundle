<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Extension;

use Sg\DatatablesBundle\Datatable\OptionsTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Select
{
    use OptionsTrait;

    //-------------------------------------------------
    // DataTables - Select Extension - select
    //-------------------------------------------------

    /**
     * Indicate if the selected items will be removed when clicking outside of the table.
     *
     * @var bool|null
     */
    protected $blurable;

    /**
     * Set the class name that will be applied to selected items.
     *
     * @var string|null
     */
    protected $className;

    /**
     * Enable / disable the display for item selection information in the table summary.
     *
     * @var bool|null
     */
    protected $info;

    /**
     * Set which table items to select (rows, columns or cells).
     *
     * @var string|null
     */
    protected $items;

    /**
     * Set the element selector used for mouse event capture to select items.
     *
     * @var string|null
     */
    protected $selector;

    /**
     * Set the selection style for end user interaction with the table.
     *
     * @var string|null
     */
    protected $style;

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
        $resolver->setDefaults(
            [
                'blurable' => null,
                'class_name' => null,
                'info' => null,
                'items' => null,
                'selector' => null,
                'style' => null,
            ]
        );

        $resolver->setAllowedTypes('blurable', ['boolean', 'null']);
        $resolver->setAllowedTypes('class_name', ['string', 'null']);
        $resolver->setAllowedTypes('info', ['boolean', 'null']);
        $resolver->setAllowedTypes('items', ['string', 'null']);
        $resolver->setAllowedValues('items', ['row', 'column', 'cell']);
        $resolver->setAllowedTypes('selector', ['string', 'null']);
        $resolver->setAllowedTypes('style', ['string', 'null']);
        $resolver->setAllowedValues('style', ['api', 'single', 'multi', 'os', 'multi+shift']);

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return bool|null
     */
    public function getBlurable()
    {
        return $this->blurable;
    }

    /**
     * @param string|null $blurable
     *
     * @return $this
     */
    public function setBlurable($blurable)
    {
        $this->blurable = $blurable;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string|null $className
     *
     * @return $this
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param bool|null $info
     *
     * @return $this
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param string|null $items
     *
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * @param string|null $selector
     *
     * @return $this
     */
    public function setSelector($selector)
    {
        $this->selector = $selector;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param string|null $style
     *
     * @return $this
     */
    public function setStyle($style)
    {
        $this->style = $style;

        return $this;
    }
}
