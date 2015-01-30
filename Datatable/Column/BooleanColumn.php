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

use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class BooleanColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class BooleanColumn extends AbstractColumn
{
    /**
     * The icon for a value that is true.
     *
     * @var string
     */
    protected $trueIcon;

    /**
     * The icon for a value that is false.
     *
     * @var string
     */
    protected $falseIcon;

    /**
     * The label for a value that is true.
     *
     * @var string
     */
    protected $trueLabel;

    /**
     * The label for a value that is false.
     *
     * @var string
     */
    protected $falseLabel;


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        if (empty($data) || !is_string($data)) {
            throw new InvalidArgumentException("setData(): Expecting non-empty string.");
        }

        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRender($render)
    {
        if (empty($render) || !is_string($render)) {
            throw new InvalidArgumentException("setRender(): Expecting non-empty string.");
        }

        $this->render = $render;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaults()
    {
        $this->setClassName("");
        $this->setContentPadding("");
        $this->setDefaultContent("");
        $this->setName("");
        $this->setOrderable(true);
        $this->setRender("render_boolean");
        $this->setSearchable(true);
        $this->setTitle("");
        $this->setType("");
        $this->setVisible(true);
        $this->setWidth("");
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
        if (array_key_exists("class", $options)) {
            $this->setClassName($options["class"]);
        }
        if (array_key_exists("padding", $options)) {
            $this->setContentPadding($options["padding"]);
        }
        if (array_key_exists("name", $options)) {
            $this->setName($options["name"]);
        }
        if (array_key_exists("orderable", $options)) {
            $this->setOrderable($options["orderable"]);
        }
        if (array_key_exists("render", $options)) {
            $this->setRender($options["render"]);
        }
        if (array_key_exists("searchable", $options)) {
            $this->setSearchable($options["searchable"]);
        }
        if (array_key_exists("title", $options)) {
            $this->setTitle($options["title"]);
        }
        if (array_key_exists("type", $options)) {
            $this->setType($options["type"]);
        }
        if (array_key_exists("visible", $options)) {
            $this->setVisible($options["visible"]);
        }
        if (array_key_exists("width", $options)) {
            $this->setWidth($options["width"]);
        }
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
