<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Editable;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TextEditable
 *
 * @package Sg\DatatablesBundle\Datatable\Editable
 */
class TextEditable extends AbstractEditable
{
    /**
     * Whether to show clear button.
     * Default: true
     *
     * Currently not usable: x-editable bug https://github.com/vitalets/x-editable/issues/977
     *
     * @var bool
     */
    protected $clear;

    /**
     * Placeholder attribute of input. Shown when input is empty.
     * Default: null
     *
     * @var null|string
     */
    protected $placeholder;

    //-------------------------------------------------
    // FilterInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'text';
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
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'clear' => true,
            'placeholder' => null,
        ));

        $resolver->setAllowedTypes('clear', 'bool');
        $resolver->setAllowedTypes('placeholder', array('null', 'string'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get clear.
     *
     * @return bool
     */
    public function isClear()
    {
        return $this->clear;
    }

    /**
     * Set clear.
     *
     * @param bool $clear
     *
     * @return $this
     */
    public function setClear($clear)
    {
        $this->clear = $clear;

        return $this;
    }

    /**
     * Get placeholder.
     *
     * @return null|string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * Set placeholder.
     *
     * @param null|string $placeholder
     *
     * @return $this
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }
}
