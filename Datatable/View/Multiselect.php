<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\View;

use Exception;

/**
 * Class Multiselect
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
class Multiselect
{
    /**
     * First column.
     *
     * @var string
     */
    const FIRST_COLUMN = "first";

    /**
     * Last column.
     *
     * @var string
     */
    const LAST_COLUMN = "last";


    /**
     * Enable or disable multiselect.
     *
     * @var boolean
     */
    private $enabled;

    /**
     * Position of the multiselect column (first or last).
     *
     * @var string
     */
    private $position;

    /**
     * All actions.
     *
     * @var array
     */
    private $actions;

    /**
     * HTML attributes.
     *
     * @var array
     */
    private $attributes;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param bool $enabled
     */
    public function __construct($enabled = false)
    {
        $this->enabled = (boolean) $enabled;
        $this->position = self::FIRST_COLUMN;
        $this->actions = array();
        $this->attributes = array();
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Add action.
     *
     * @param string $title The title for the form select field
     * @param string $route The actions route
     *
     * @return $this
     */
    public function addAction($title, $route)
    {
        $this->enabled = true;
        $this->actions[$title] = $route;

        return $this;
    }

    /**
     * Get actions.
     *
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Set enabled.
     *
     * @param boolean $enabled
     *
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (boolean) $enabled;

        return $this;
    }

    /**
     * Get enabled.
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return (boolean) $this->enabled;
    }

    /**
     * Set position.
     *
     * @param string $position
     *
     * @return $this
     * @throws Exception
     */
    public function setPosition($position)
    {
        if (self::FIRST_COLUMN == $position || self::LAST_COLUMN == $position) {
            $this->position = $position;
        } else {
            throw new Exception("The position {$position} is not supported.");
        }

        return $this;
    }

    /**
     * Get position.
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set attributes.
     *
     * @param array $attributes
     *
     * @throws Exception
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        if (array_key_exists("type", $this->attributes)) {
            throw new Exception("The type attribute is not supported.");
        }

        if (array_key_exists("value", $this->attributes)) {
            throw new Exception("The value attribute is not supported.");
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
     * Get attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
