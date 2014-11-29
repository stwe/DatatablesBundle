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
 * Class AbstractDataRenderColumn
 *
 * Base class for all columns with:
 * (1) data   = string
 * (2) render = string
 *
 * Examples: Boolean class (Boolean.php), DateTime class (DateTime.php) and
 *           Timeago class (Timeago.php)
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
abstract class AbstractDataRenderColumn extends AbstractColumn
{
    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setRender($render)
    {
        if (empty($render) || !is_string($render)) {
            throw new InvalidArgumentException("setRender(): String expected.");
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
        $this->setRender("your_render_function");
        $this->setSearchable(true);
        $this->setTitle("");
        $this->setType("");
        $this->setVisible(true);
        $this->setWidth("");

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

        return $this;
    }
}
