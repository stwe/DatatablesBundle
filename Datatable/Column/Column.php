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
 * Class Column
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class Column extends AbstractColumn
{
    /**
     * Default content.
     *
     * @var string
     */
    protected $default;


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
        if (null === $render || is_string($render) && $render !== "") {
            $this->render = $render;
        } else {
            throw new InvalidArgumentException("setRender(): Expecting non-empty string or null.");
        }

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
        $this->setRender(null);
        $this->setSearchable(true);
        $this->setTitle("");
        $this->setType("");
        $this->setVisible(true);
        $this->setWidth("");
        $this->setDefault("");

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
        if (array_key_exists("default", $options)) {
            $this->setDefault($options["default"]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return "SgDatatablesBundle:Column:column.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return "column";
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get default.
     *
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Set default.
     *
     * @param string $default
     *
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }
}
