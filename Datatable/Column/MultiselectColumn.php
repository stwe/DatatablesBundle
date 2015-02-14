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
 * Class MultiselectColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class MultiselectColumn extends ActionColumn
{
    /**
     * HTML attributes for the checkboxes.
     *
     * @var array
     */
    protected $attributes;


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setDefaults()
    {
        parent::setDefaults();

        $this->setAttributes(array());

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
        if (array_key_exists("type", $options)) {
            $this->setType($options["type"]);
        }
        if (array_key_exists("visible", $options)) {
            $this->setVisible($options["visible"]);
        }
        if (array_key_exists("width", $options)) {
            $this->setWidth($options["width"]);
        }
        if (array_key_exists("start_html", $options)) {
            $this->setStartWrapper($options["start_html"]);
        }
        if (array_key_exists("end_html", $options)) {
            $this->setEndWrapper($options["end_html"]);
        }
        if (array_key_exists("actions", $options)) {
            $this->setActions($options["actions"]);
        }
        if (array_key_exists("attributes", $options)) {
            $this->setAttributes($options["attributes"]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return "SgDatatablesBundle:Column:multiselect.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return "multiselect";
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set checkbox attributes.
     *
     * @param array $attributes
     *
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        if (array_key_exists("type", $this->attributes)) {
            throw new InvalidArgumentException("The 'type' attribute is not supported.");
        }
        if (array_key_exists("value", $this->attributes)) {
            throw new InvalidArgumentException("The 'value' attribute is not supported.");
        }
        if (array_key_exists("name", $this->attributes)) {
            $this->attributes["name"] = "multiselect_checkbox " . $this->attributes["name"];
        } else {
            $this->attributes["name"] = "multiselect_checkbox";
        }
        if (array_key_exists("class", $this->attributes)) {
            $this->attributes["class"] = "multiselect_checkbox " . $this->attributes["class"];
        } else {
            $this->attributes["class"] = "multiselect_checkbox";
        }

        return $this;
    }

    /**
     * Get checkbox attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
