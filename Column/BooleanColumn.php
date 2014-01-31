<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Column;

use Sg\DatatablesBundle\Column\AbstractColumn as BaseColumn;

use Exception;

/**
 * Class BooleanColumn
 *
 * @package Sg\DatatablesBundle\Column
 */
class BooleanColumn extends BaseColumn
{
    /**
     * The icon for a value that is true.
     *
     * @var null|string
     */
    private $trueIcon;

    /**
     * The icon for a value that is false.
     *
     * @var null|string
     */
    private $falseIcon;

    /**
     * The label for a value that is true.
     *
     * @var null|string
     */
    private $trueLabel;

    /**
     * The label for a value that is false.
     *
     * @var null|string
     */
    private $falseLabel;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param null|string $property An entity's property
     *
     * @throws Exception
     */
    public function __construct($property = null)
    {
        if (null == $property) {
            throw new Exception("The entity's property can not be null.");
        }

        parent::__construct($property);
    }


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'boolean';
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        if (array_key_exists('render', $options)) {
            if (null == $options['render']) {
                throw new Exception('The render option can not be null.');
            }
        }

        parent::setOptions($options);

        if (array_key_exists('true_icon', $options)) {
            $this->setTrueIcon($options['true_icon']);
        }
        if (array_key_exists('false_icon', $options)) {
            $this->setFalseIcon($options['false_icon']);
        }
        if (array_key_exists('true_label', $options)) {
            $this->setTrueLabel($options['true_label']);
        }
        if (array_key_exists('false_label', $options)) {
            $this->setFalseLabel($options['false_label']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaults()
    {
        parent::setDefaults();

        $this->setMRender('render_boolean_icons');

        $this->setTrueIcon(null);
        $this->setFalseIcon(null);
        $this->setTrueLabel(null);
        $this->setFalseLabel(null);
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set false icon.
     *
     * @param null|string $falseIcon
     *
     * @return $this
     */
    public function setFalseIcon($falseIcon)
    {
        $this->falseIcon = $falseIcon;

        return $this;
    }

    /**
     * Get false icon.
     *
     * @return null|string
     */
    public function getFalseIcon()
    {
        return $this->falseIcon;
    }

    /**
     * Set true icon.
     *
     * @param null|string $trueIcon
     *
     * @return $this
     */
    public function setTrueIcon($trueIcon)
    {
        $this->trueIcon = $trueIcon;

        return $this;
    }

    /**
     * Get true icon.
     *
     * @return null|string
     */
    public function getTrueIcon()
    {
        return $this->trueIcon;
    }

    /**
     * Set false label.
     *
     * @param null|string $falseLabel
     *
     * @return $this
     */
    public function setFalseLabel($falseLabel)
    {
        $this->falseLabel = $falseLabel;

        return $this;
    }

    /**
     * Get false label.
     *
     * @return null|string
     */
    public function getFalseLabel()
    {
        return $this->falseLabel;
    }

    /**
     * Set true label.
     *
     * @param null|string $trueLabel
     *
     * @return $this
     */
    public function setTrueLabel($trueLabel)
    {
        $this->trueLabel = $trueLabel;

        return $this;
    }

    /**
     * Get false label.
     *
     * @return null|string
     */
    public function getTrueLabel()
    {
        return $this->trueLabel;
    }
}