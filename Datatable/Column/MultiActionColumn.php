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
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Sg\DatatablesBundle\Datatable\Column\AbstractColumn as BaseColumn;

use Exception;

/**
 * Class ActionColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class MultiActionColumn extends BaseColumn
{
    /**
     * HTML attributes.
     *
     * @var array
     */
    private $attributes;

    /**
     * Actions
     *
     * @var array
     */
    private $actions;

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
     *
     * @throws Exception
     */
    public function __construct($property = null)
    {
        if (null != $property) {
            throw new Exception("The entity's property should be null.");
        }

        parent::__construct($property);

        $this->addAllowedOption("actions");
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
        return "multiaction";
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);

        $options = array_intersect_key($options, array_flip($this->getAllowedOptions()));

        if (array_key_exists("attributes", $options)) {
            $this->setAttributes($options["attributes"]);
        }
        if (array_key_exists("actions", $options)) {
            $this->importActions($options["actions"]);
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

        $this->setAttributes(array());
        $this->setRenderConditions(array());

        return $this;
    }

    protected function importActions($actions)
    {
        if (!is_array($actions)) {
            $actions = array($actions);
        }

        foreach ($actions as $action) {
            $actionColumn = new ActionColumn();
            $this->actions[] = $actionColumn->setOptions($action);
        }
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

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

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }
}