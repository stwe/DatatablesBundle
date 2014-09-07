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
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Sg\DatatablesBundle\Datatable\Column\AbstractColumn as BaseColumn;

/**
 * Class VirtualColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class VirtualColumn extends BaseColumn
{
    /**
     * An action label.
     *
     * @var string
     */
    private $label;

    /**
     * HTML attributes.
     *
     * @var array
     */
    private $attributes;

    /**
     * Render only if parameter / conditions are TRUE
     *
     * @var array
     */
    private $renderConditions;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param null|string $property An entity's property
     */
    public function __construct($property = null)
    {
        parent::__construct($property);

        $this->addAllowedOption("label");
        $this->addAllowedOption("attributes");
        $this->addAllowedOption("renderif");
    }


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getColumnClassName()
    {
        return "virtual";
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);

        $options = array_intersect_key($options, array_flip($this->getAllowedOptions()));

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
    public function setDefaults()
    {
        parent::setDefaults();

        $this->setSearchable(false);
        $this->setOrderable(false);

        $this->setLabel("");
        $this->setAttributes(array());
        $this->setRenderConditions(array());

        return $this;
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