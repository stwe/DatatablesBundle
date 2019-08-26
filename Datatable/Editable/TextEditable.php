<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Editable;

use Symfony\Component\OptionsResolver\OptionsResolver;

class TextEditable extends AbstractEditable
{
    /**
     * Whether to show clear button.
     * Default: true.
     *
     * Currently not usable: x-editable bug https://github.com/vitalets/x-editable/issues/977
     *
     * @var bool
     */
    protected $clear;

    /**
     * Placeholder attribute of input. Shown when input is empty.
     * Default: null.
     *
     * @var string|null
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
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'clear' => true,
            'placeholder' => null,
        ]);

        $resolver->setAllowedTypes('clear', 'bool');
        $resolver->setAllowedTypes('placeholder', ['null', 'string']);

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return bool
     */
    public function isClear()
    {
        return $this->clear;
    }

    /**
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
     * @return string|null
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param string|null $placeholder
     *
     * @return $this
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }
}
