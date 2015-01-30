<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Tomáš Polívka <draczris@gmail.com>
 * @author stwe
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class DateTimeColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class DateTimeColumn extends AbstractColumn
{
    /**
     * DateTime format string.
     *
     * @link http://momentjs.com/
     *
     * @var string
     */
    protected $dateFormat;


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
        $this->setRender("render_datetime");
        $this->setSearchable(true);
        $this->setTitle("");
        $this->setType("");
        $this->setVisible(true);
        $this->setWidth("");
        $this->setDateFormat("lll");

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
        if (array_key_exists("format", $options)) {
            $this->setDateFormat($options["format"]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return "SgDatatablesBundle:Column:datetime.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return "datetime";
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set date format.
     *
     * @param string $dateFormat
     *
     * @return $this
     */
    public function setDateFormat($dateFormat)
    {
        if (empty($dateFormat) || !is_string($dateFormat)) {
            throw new InvalidArgumentException("setDateFormat(): Expecting non-empty string.");
        }

        $this->dateFormat = $dateFormat;

        return $this;
    }

    /**
     * Get date format.
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }
}
