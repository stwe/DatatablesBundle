<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class ProgressBarColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ProgressBarColumn extends AbstractColumn
{
    /**
     * @var string
     */
    protected $barClasses;

    /**
     * @var string
     */
    protected $valueMin;

    /**
     * @var string
     */
    protected $valueMax;

    /**
     * @var boolean
     */
    protected $label;

    /**
     * @var boolean
     */
    protected $multiColor;

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        if (empty($data) || !is_string($data)) {
            throw new InvalidArgumentException('setData(): Expecting non-empty string.');
        }

        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Column:progress_bar.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'progress_bar';
    }

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->remove('default_content');

        $resolver->setDefaults(array(
            'render' => 'render_progress_bar',
            'filter' => array('text', array(
                'search_type' => 'eq'
            )),
            'bar_classes' => '',
            'value_min' => '0',
            'value_max' => '100',
            'label' => true,
            'multi_color' => false
        ));

        $resolver->setAllowedTypes('filter', 'array');
        $resolver->setAllowedTypes('bar_classes', 'string');
        $resolver->setAllowedTypes('value_min', 'string');
        $resolver->setAllowedTypes('value_max', 'string');
        $resolver->setAllowedTypes('label', 'bool');
        $resolver->setAllowedTypes('multi_color', 'bool');

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set bar classes.
     *
     * @param string $barClasses
     *
     * @return $this
     */
    public function setBarClasses($barClasses)
    {
        $this->barClasses = $barClasses;

        return $this;
    }

    /**
     * Get bar classes.
     *
     * @return string
     */
    public function getBarClasses()
    {
        return $this->barClasses;
    }

    /**
     * Set valueMin.
     *
     * @param string $valueMin
     *
     * @return $this
     */
    public function setValueMin($valueMin)
    {
        $this->valueMin = $valueMin;

        return $this;
    }

    /**
     * Get valueMin.
     *
     * @return string
     */
    public function getValueMin()
    {
        return $this->valueMin;
    }

    /**
     * Set valueMax.
     *
     * @param string $valueMax
     *
     * @return $this
     */
    public function setValueMax($valueMax)
    {
        $this->valueMax = $valueMax;

        return $this;
    }

    /**
     * Get valueMax.
     *
     * @return string
     */
    public function getValueMax()
    {
        return $this->valueMax;
    }

    /**
     * Set label.
     *
     * @param boolean $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = (boolean) $label;

        return $this;
    }

    /**
     * Get label.
     *
     * @return boolean
     */
    public function getLabel()
    {
        return (boolean) $this->label;
    }

    /**
     * Set multiColor.
     *
     * @param boolean $multiColor
     *
     * @return $this
     */
    public function setMultiColor($multiColor)
    {
        $this->multiColor = (boolean) $multiColor;

        return $this;
    }

    /**
     * Get multiColor.
     *
     * @return boolean
     */
    public function isMultiColor()
    {
        return (boolean) $this->multiColor;
    }
}
