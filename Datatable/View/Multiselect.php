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
class Multiselect implements MultiselectInterface
{
    /**
     * First column.
     *
     * @var string
     */
    const FIRST_COLUMN = 'first';

    /**
     * Last column.
     *
     * @var string
     */
    const LAST_COLUMN = 'last';

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
        $this->enabled = $enabled;
        $this->position = self::FIRST_COLUMN;
        $this->actions = array();
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function addAction($title, $route)
    {
        $this->enabled = true;
        $this->actions[$title] = $route;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (boolean) $enabled;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabled()
    {
        return (boolean) $this->enabled;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }
}