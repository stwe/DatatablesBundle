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

use Sg\DatatablesBundle\Datatable\Extension\Buttons;
use Sg\DatatablesBundle\Datatable\Extension\Responsive;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Extensions
 *
 * @package Sg\DatatablesBundle\Datatable
 */
class Extensions
{
    /**
     * Use the OptionsResolver.
     */
    use OptionsTrait;

    //-------------------------------------------------
    // DataTables - Extensions
    //-------------------------------------------------

    /**
     * The Buttons extension.
     * Default: null
     *
     * @var null|array|bool|Buttons
     */
    protected $buttons;

    /**
     * The Responsive Extension.
     * Automatically optimise the layout for different screen sizes.
     * Default: null
     *
     * @var null|array|bool|Responsive
     */
    protected $responsive;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Extensions constructor.
     */
    public function __construct()
    {
        $this->initOptions();
    }

    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * Config options.
     *
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'buttons' => null,
            'responsive' => null,
        ));

        $resolver->setAllowedTypes('buttons', array('null', 'array', 'bool'));
        $resolver->setAllowedTypes('responsive', array('null', 'array', 'bool'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get buttons.
     *
     * @return null|array|bool|Buttons
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * Set buttons.
     *
     * @param null|array|bool $buttons
     *
     * @return $this
     */
    public function setButtons($buttons)
    {
        if (is_array($buttons)) {
            $newButton = new Buttons();
            $this->buttons = $newButton->set($buttons);
        } else {
            $this->buttons = $buttons;
        }

        return $this;
    }

    /**
     * Get responsive.
     *
     * @return null|array|bool|Responsive
     */
    public function getResponsive()
    {
        return $this->responsive;
    }

    /**
     * Set responsive.
     *
     * @param null|array|bool $responsive
     *
     * @return $this
     */
    public function setResponsive($responsive)
    {
        if (is_array($responsive)) {
            $newResponsive = new Responsive();
            $this->responsive = $newResponsive->set($responsive);
        } else {
            $this->responsive = $responsive;
        }

        return $this;
    }
}
