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

/**
 * Class BooleanColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class BooleanColumn extends AbstractDataRenderColumn
{
    /**
     * The icon for a value that is true.
     *
     * @var string
     */
    private $trueIcon;

    /**
     * The icon for a value that is false.
     *
     * @var string
     */
    private $falseIcon;

    /**
     * The label for a value that is true.
     *
     * @var string
     */
    private $trueLabel;

    /**
     * The label for a value that is false.
     *
     * @var string
     */
    private $falseLabel;


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setDefaults()
    {
        parent::setDefaults();

        $this->setRender("render_boolean");

        $this->setTrueIcon("");
        $this->setFalseIcon("");
        $this->setTrueLabel("");
        $this->setFalseLabel("");

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);

        if (array_key_exists("true_icon", $options)) {
            $this->setTrueIcon($options["true_icon"]);
        }
        if (array_key_exists("false_icon", $options)) {
            $this->setFalseIcon($options["false_icon"]);
        }
        if (array_key_exists("true_label", $options)) {
            $this->setTrueLabel($options["true_label"]);
        }
        if (array_key_exists("false_label", $options)) {
            $this->setFalseLabel($options["false_label"]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return "SgDatatablesBundle:Column:boolean.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return "boolean";
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set false icon.
     *
     * @param string $falseIcon
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
     * @return string
     */
    public function getFalseIcon()
    {
        return $this->falseIcon;
    }

    /**
     * Set true icon.
     *
     * @param string $trueIcon
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
     * @return string
     */
    public function getTrueIcon()
    {
        return $this->trueIcon;
    }

    /**
     * Set false label.
     *
     * @param string $falseLabel
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
     * @return string
     */
    public function getFalseLabel()
    {
        return $this->falseLabel;
    }

    /**
     * Set true label.
     *
     * @param string $trueLabel
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
     * @return string
     */
    public function getTrueLabel()
    {
        return $this->trueLabel;
    }
}
