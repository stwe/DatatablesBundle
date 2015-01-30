<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author nicodmf
 * @author stwe
 */

namespace Sg\DatatablesBundle\Datatable\Column;

/**
 * Class VirtualColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class VirtualColumn extends Column
{
    /**
     * An action label.
     *
     * @var string
     */
    protected $label;

    /**
     * HTML attributes.
     *
     * @var array
     */
    protected $attributes;

    /**
     * Render only if parameter / conditions are TRUE
     *
     * @var array
     */
    protected $renderConditions;


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setDefaults()
    {
        parent::setDefaults();

        $this->setOrderable(false);
        $this->setSearchable(false);
        $this->setLabel("");
        $this->setAttributes(array());
        $this->setRenderConditions(array());

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
        if (array_key_exists("render", $options)) {
            $this->setRender($options["render"]);
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
        if (array_key_exists("default", $options)) {
            $this->setDefault($options["default"]);
        }
        if (array_key_exists("label", $options)) {
            $this->setLabel($options["label"]);
        }
        if (array_key_exists("attributes", $options)) {
            $this->setAttributes($options["attributes"]);
        }
        if (array_key_exists("renderif", $options)) {
            $this->setRenderConditions($options["renderif"]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return "virtual";
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set label.
     *
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set attributes.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Get attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set render conditions.
     *
     * @param array $renderConditions
     *
     * @return $this
     */
    public function setRenderConditions(array $renderConditions)
    {
        $this->renderConditions = $renderConditions;

        return $this;
    }

    /**
     * Get render conditions.
     *
     * @return array
     */
    public function getRenderConditions()
    {
        return $this->renderConditions;
    }
}
