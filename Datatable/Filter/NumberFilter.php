<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Filter;

use Symfony\Component\OptionsResolver\OptionsResolver;

class NumberFilter extends TextFilter
{
    /**
     * Minimum value.
     * Default: '0'.
     *
     * @var string
     */
    protected $min;

    /**
     * Maximum value.
     * Default: '100'.
     *
     * @var string
     */
    protected $max;

    /**
     * The Step scale factor of the slider.
     * Default: '1'.
     *
     * @var string
     */
    protected $step;

    /**
     * Determines whether a label with the current value is displayed.
     * Default: false.
     *
     * @var bool
     */
    protected $showLabel;

    /**
     * Pre-defined values.
     * Default: null.
     *
     * @var array|null
     */
    protected $datalist;

    /**
     * The <input> type.
     * Default: 'number'.
     *
     * @var string
     */
    protected $type;

    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->remove('placeholder');
        $resolver->remove('placeholder_text');

        $resolver->setDefaults([
            'min' => '0',
            'max' => '100',
            'step' => '1',
            'show_label' => false,
            'datalist' => null,
            'type' => 'number',
        ]);

        $resolver->setAllowedTypes('min', 'string');
        $resolver->setAllowedTypes('max', 'string');
        $resolver->setAllowedTypes('step', 'string');
        $resolver->setAllowedTypes('show_label', 'bool');
        $resolver->setAllowedTypes('datalist', ['null', 'array']);
        $resolver->setAllowedTypes('type', 'string');

        $resolver->addAllowedValues('type', ['number', 'range']);

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return string
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @param string $min
     *
     * @return $this
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @return string
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param string $max
     *
     * @return $this
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * @return string
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * @param string $step
     *
     * @return $this
     */
    public function setStep($step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowLabel()
    {
        return $this->showLabel;
    }

    /**
     * @param bool $showLabel
     *
     * @return $this
     */
    public function setShowLabel($showLabel)
    {
        $this->showLabel = $showLabel;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getDatalist()
    {
        return $this->datalist;
    }

    /**
     * @param array|null $datalist
     *
     * @return $this
     */
    public function setDatalist($datalist)
    {
        $this->datalist = $datalist;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
