<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Filter;

use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SliderFilter
 *
 * @package Sg\DatatablesBundle\Datatable\Filter
 */
class SliderFilter extends AbstractFilter
{
    /**
     * Minimum possible value.
     *
     * @var float
     */
    protected $min;

    /**
     * Maximum possible value.
     *
     * @var float
     */
    protected $max;

    /**
     * Increment step.
     *
     * @var float
     */
    protected $step;

    /**
     * Number of digits after the decimal of step value.
     *
     * @var float
     */
    protected $precision;

    /**
     * Set the orientation. Accepts 'vertical' or 'horizontal'.
     *
     * @var string
     */
    protected $orientation;

    /**
     * Initial value. Use array to have a range slider.
     *
     * @var float|array
     */
    protected $value;

    /**
     * Make range slider.
     *
     * @var boolean
     */
    protected $range;

    /**
     * Selection placement. Accepts: 'before', 'after' or 'none'.
     *
     * @var string
     */
    protected $selection;

    /**
     * Whether to show the tooltip on drag, hide the tooltip, or always show the tooltip.
     * Accepts: 'show', 'hide', or 'always'.
     *
     * @var string
     */
    protected $tooltip;

    /**
     * If false show one tootip if true show two tooltips one for each handler.
     *
     * @var boolean
     */
    protected $tooltipSplit;

    /**
     * Position of tooltip, relative to slider.
     * Accepts 'top'/'bottom' for horizontal sliders and 'left'/'right' for vertically orientated sliders.
     *
     * @var string|null
     */
    protected $tooltipPosition;

    /**
     * Handle shape. Accepts: 'round', 'square', 'triangle' or 'custom'.
     *
     * @var string
     */
    protected $handle;

    /**
     * Whether or not the slider should be reversed.
     *
     * @var boolean
     */
    protected $reversed;

    /**
     * Whether or not the slider is initially enabled.
     *
     * @var boolean
     */
    protected $enabled;

    /**
     * Formatter callback. Return the value wanted to be displayed in the tooltip.
     *
     * @var string
     */
    protected $formatter;

    /**
     * The natural order is used for the arrow keys.
     *
     * @var boolean
     */
    protected $naturalArrowKeys;

    /**
     * Used to define the values of ticks.
     *
     * @var array
     */
    protected $ticks;

    /**
     * Defines the positions of the tick values in percentages.
     *
     * @var array
     */
    protected $ticksPositions;

    /**
     * Defines the labels below the tick marks.
     *
     * @var array
     */
    protected $ticksLabels;

    /**
     * Used to define the snap bounds of a tick. Snaps to the tick if value is within these bounds.
     *
     * @var float
     */
    protected $ticksSnapBounds;

    /**
     * Set to 'logarithmic' to use a logarithmic scale.
     *
     * @var string
     */
    protected $scale;

    /**
     * Focus the appropriate slider handle after a value change.
     *
     * @var boolean
     */
    protected $focus;

    /**
     * ARIA labels for the slider handle's, Use array for multiple values in a range slider.
     *
     * @var string|array
     */
    protected $labelledby;

    //-------------------------------------------------
    // FilterInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Filters:filter_slider.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function addAndExpression(Andx $andExpr, QueryBuilder $pivot, $searchField, $searchValue, &$i)
    {
        if (true === $this->range) {
            $array = explode(',', $searchValue);
            list($searchMin, $searchMax) = $array;

            $andExpr = $this->getBetweenAndExpression($andExpr, $pivot, $searchField, $searchMin, $searchMax, $i);
            $i += 2;
        } else {
            $andExpr = $this->getAndExpression($andExpr, $pivot, $searchField, $searchValue, $i);
            $i++;
        }

        return $andExpr;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'slider';
    }

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'search_type' => 'eq',
            'property' => '',
            'class' => '',
            'cancel_button' => false,
            'min' => 0.0,
            'max' => 10.0,
            'step' => 1.0,
            'precision' => 0.0,
            'orientation' => 'horizontal',
            'value' => 0.0,
            'range' => false,
            'selection' => 'before',
            'tooltip' => 'show',
            'tooltip_split' => false,
            'tooltip_position' => null,
            'handle' => 'round',
            'reversed' => false,
            'enabled' => true,
            'formatter' => '',
            'natural_arrow_keys' => false,
            'ticks' => array(),
            'ticks_positions' => array(),
            'ticks_labels' => array(),
            'ticks_snap_bounds' => 0.0,
            'scale' => 'linear',
            'focus' => false,
            'labelledby' => null,
        ));

        $resolver->setAllowedTypes('search_type', 'string');
        $resolver->setAllowedTypes('property', 'string');
        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedTypes('cancel_button', 'bool');
        $resolver->setAllowedTypes('min', 'float');
        $resolver->setAllowedTypes('max', 'float');
        $resolver->setAllowedTypes('step', 'float');
        $resolver->setAllowedTypes('precision', 'float');
        $resolver->setAllowedTypes('orientation', 'string');
        $resolver->setAllowedTypes('value', array('float', 'array'));
        $resolver->setAllowedTypes('range', 'bool');
        $resolver->setAllowedTypes('selection', 'string');
        $resolver->setAllowedTypes('tooltip', 'string');
        $resolver->setAllowedTypes('tooltip_split', 'bool');
        $resolver->setAllowedTypes('tooltip_position', array('string', 'null'));
        $resolver->setAllowedTypes('handle', 'string');
        $resolver->setAllowedTypes('reversed', 'bool');
        $resolver->setAllowedTypes('enabled', 'bool');
        $resolver->setAllowedTypes('formatter', 'string');
        $resolver->setAllowedTypes('natural_arrow_keys', 'bool');
        $resolver->setAllowedTypes('ticks', 'array');
        $resolver->setAllowedTypes('ticks_positions', 'array');
        $resolver->setAllowedTypes('ticks_labels', 'array');
        $resolver->setAllowedTypes('ticks_snap_bounds', 'float');
        $resolver->setAllowedTypes('scale', 'string');
        $resolver->setAllowedTypes('focus', 'bool');
        $resolver->setAllowedTypes('labelledby', array('string', 'array', 'null'));

        $resolver->setAllowedValues('search_type', array('like', 'notLike', 'eq', 'neq', 'lt', 'lte', 'gt', 'gte', 'in', 'notIn', 'isNull', 'isNotNull'));
        $resolver->setAllowedValues('orientation', array('vertical', 'horizontal'));
        $resolver->setAllowedValues('selection', array('before', 'after', 'none'));
        $resolver->setAllowedValues('tooltip', array('show', 'hide', 'always'));
        $resolver->setAllowedValues('tooltip_position', array('top', 'bottom', 'left', 'right', null));
        $resolver->setAllowedValues('handle', array('round', 'square', 'triangle', 'custom'));
        $resolver->setAllowedValues('scale', array('linear', 'logarithmic'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return float
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @param float $min
     *
     * @return $this
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @return float
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param float $max
     *
     * @return $this
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * @return float
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * @param float $step
     *
     * @return $this
     */
    public function setStep($step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @param float $precision
     *
     * @return $this
     */
    public function setPrecision($precision)
    {
        $this->precision = $precision;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * @param string $orientation
     *
     * @return $this
     */
    public function setOrientation($orientation)
    {
        $this->orientation = $orientation;

        return $this;
    }

    /**
     * @return array|float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param array|float $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getRange()
    {
        return $this->range;
    }

    /**
     * @param boolean $range
     *
     * @return $this
     */
    public function setRange($range)
    {
        $this->range = $range;

        return $this;
    }

    /**
     * @return string
     */
    public function getSelection()
    {
        return $this->selection;
    }

    /**
     * @param string $selection
     *
     * @return $this
     */
    public function setSelection($selection)
    {
        $this->selection = $selection;

        return $this;
    }

    /**
     * @return string
     */
    public function getTooltip()
    {
        return $this->tooltip;
    }

    /**
     * @param string $tooltip
     *
     * @return $this
     */
    public function setTooltip($tooltip)
    {
        $this->tooltip = $tooltip;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getTooltipSplit()
    {
        return $this->tooltipSplit;
    }

    /**
     * @param boolean $tooltipSplit
     *
     * @return $this
     */
    public function setTooltipSplit($tooltipSplit)
    {
        $this->tooltipSplit = $tooltipSplit;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getTooltipPosition()
    {
        return $this->tooltipPosition;
    }

    /**
     * @param null|string $tooltipPosition
     *
     * @return $this
     */
    public function setTooltipPosition($tooltipPosition)
    {
        $this->tooltipPosition = $tooltipPosition;

        return $this;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @param string $handle
     *
     * @return $this
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getReversed()
    {
        return $this->reversed;
    }

    /**
     * @param boolean $reversed
     *
     * @return $this
     */
    public function setReversed($reversed)
    {
        $this->reversed = $reversed;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     *
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * @param string $formatter
     *
     * @return $this
     */
    public function setFormatter($formatter)
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getNaturalArrowKeys()
    {
        return $this->naturalArrowKeys;
    }

    /**
     * @param boolean $naturalArrowKeys
     *
     * @return $this
     */
    public function setNaturalArrowKeys($naturalArrowKeys)
    {
        $this->naturalArrowKeys = $naturalArrowKeys;

        return $this;
    }

    /**
     * @return array
     */
    public function getTicks()
    {
        return $this->ticks;
    }

    /**
     * @param array $ticks
     *
     * @return $this
     */
    public function setTicks($ticks)
    {
        $this->ticks = $ticks;

        return $this;
    }

    /**
     * @return array
     */
    public function getTicksPositions()
    {
        return $this->ticksPositions;
    }

    /**
     * @param array $ticksPositions
     *
     * @return $this
     */
    public function setTicksPositions($ticksPositions)
    {
        $this->ticksPositions = $ticksPositions;

        return $this;
    }

    /**
     * @return array
     */
    public function getTicksLabels()
    {
        return $this->ticksLabels;
    }

    /**
     * @param array $ticksLabels
     *
     * @return $this
     */
    public function setTicksLabels($ticksLabels)
    {
        $this->ticksLabels = $ticksLabels;

        return $this;
    }

    /**
     * @return float
     */
    public function getTicksSnapBounds()
    {
        return $this->ticksSnapBounds;
    }

    /**
     * @param float $ticksSnapBounds
     *
     * @return $this
     */
    public function setTicksSnapBounds($ticksSnapBounds)
    {
        $this->ticksSnapBounds = $ticksSnapBounds;

        return $this;
    }

    /**
     * @return string
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * @param string $scale
     *
     * @return $this
     */
    public function setScale($scale)
    {
        $this->scale = $scale;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getFocus()
    {
        return $this->focus;
    }

    /**
     * @param boolean $focus
     *
     * @return $this
     */
    public function setFocus($focus)
    {
        $this->focus = $focus;

        return $this;
    }

    /**
     * @return array|string
     */
    public function getLabelledby()
    {
        return $this->labelledby;
    }

    /**
     * @param array|string $labelledby
     *
     * @return $this
     */
    public function setLabelledby($labelledby)
    {
        $this->labelledby = $labelledby;

        return $this;
    }
}
