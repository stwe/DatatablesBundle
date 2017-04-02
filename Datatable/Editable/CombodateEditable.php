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
 * Class CombodateEditable
 *
 * @package Sg\DatatablesBundle\Datatable\Editable
 */
class CombodateEditable extends AbstractEditable
{
    /**
     * Format used for sending value to server.
     *
     * @var string
     */
    protected $format;

    /**
     * Format used for displaying date. If not specified equals to $format.
     *
     * @var null|string
     */
    protected $viewFormat;

    /**
     * Minimum value in years dropdown.
     * Default: 1970
     *
     * @var int
     */
    protected $minYear;

    /**
     * Maximum value in years dropdown.
     * Default: 2035
     *
     * @var int
     */
    protected $maxYear;

    /**
     * Step of values in minutes dropdown.
     * Default: 5
     *
     * @var int
     */
    protected $minuteStep;

    /**
     * Step of values in seconds dropdown.
     * Default: 1
     *
     * @var int
     */
    protected $secondStep;

    /**
     * If false - number of days in dropdown is always 31.
     * If true - number of days depends on selected month and year.
     * Default: false
     *
     * @var bool
     */
    protected $smartDays;

    //-------------------------------------------------
    // FilterInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'combodate';
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
            'format' => 'YYYY-MM-DD',
            'view_format' => null,
            'min_year' => 1970,
            'max_year' => 2035,
            'minute_step' => 5,
            'second_step' => 1,
            'smart_days' => false,
        ));

        $resolver->setAllowedTypes('format', 'string');
        $resolver->setAllowedTypes('view_format', array('string', 'null'));
        $resolver->setAllowedTypes('min_year', 'int');
        $resolver->setAllowedTypes('max_year', 'int');
        $resolver->setAllowedTypes('minute_step', 'int');
        $resolver->setAllowedTypes('second_step', 'int');
        $resolver->setAllowedTypes('smart_days', 'bool');

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get format.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set format.
     *
     * @param string $format
     *
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get viewFormat.
     *
     * @return null|string
     */
    public function getViewFormat()
    {
        return $this->viewFormat;
    }

    /**
     * Set viewFormat.
     *
     * @param null|string $viewFormat
     *
     * @return $this
     */
    public function setViewFormat($viewFormat)
    {
        $this->viewFormat = $viewFormat;

        return $this;
    }

    /**
     * Get minYear.
     *
     * @return int
     */
    public function getMinYear()
    {
        return $this->minYear;
    }

    /**
     * Set minYear.
     *
     * @param int $minYear
     *
     * @return $this
     */
    public function setMinYear($minYear)
    {
        $this->minYear = $minYear;

        return $this;
    }

    /**
     * Get maxYear.
     *
     * @return int
     */
    public function getMaxYear()
    {
        return $this->maxYear;
    }

    /**
     * Set maxYear.
     *
     * @param int $maxYear
     *
     * @return $this
     */
    public function setMaxYear($maxYear)
    {
        $this->maxYear = $maxYear;

        return $this;
    }

    /**
     * Get minuteStep.
     *
     * @return int
     */
    public function getMinuteStep()
    {
        return $this->minuteStep;
    }

    /**
     * Set minuteStep.
     *
     * @param int $minuteStep
     *
     * @return $this
     */
    public function setMinuteStep($minuteStep)
    {
        $this->minuteStep = $minuteStep;

        return $this;
    }

    /**
     * Get secondStep.
     *
     * @return int
     */
    public function getSecondStep()
    {
        return $this->secondStep;
    }

    /**
     * Set secondStep.
     *
     * @param int $secondStep
     *
     * @return $this
     */
    public function setSecondStep($secondStep)
    {
        $this->secondStep = $secondStep;

        return $this;
    }

    /**
     * Get smartDays.
     *
     * @return bool
     */
    public function isSmartDays()
    {
        return $this->smartDays;
    }

    /**
     * Set smartDays.
     *
     * @param bool $smartDays
     *
     * @return $this
     */
    public function setSmartDays($smartDays)
    {
        $this->smartDays = $smartDays;

        return $this;
    }
}
