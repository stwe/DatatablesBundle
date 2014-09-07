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

use Sg\DatatablesBundle\Datatable\Column\AbstractColumn as BaseColumn;

use Exception;

/**
 * Class ActionColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ActionColumn extends BaseColumn
{
    /**
     * Start HTML.
     *
     * @var string
     */
    private $startWrapper;

    /**
     * End HTML.
     *
     * @var string
     */
    private $endWrapper;

    /**
     * The actions container.
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

        $this->addAllowedOption("start");
        $this->addAllowedOption("end");
        $this->addAllowedOption("actions");
    }


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getColumnClassName()
    {
        return "action";
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);

        $options = array_change_key_case($options, CASE_LOWER);
        $options = array_intersect_key($options, array_flip($this->getAllowedOptions()));

        if (array_key_exists("start", $options)) {
            $this->setStartWrapper($options["start"]);
        }
        if (array_key_exists("end", $options)) {
            $this->setEndWrapper($options["end"]);
        }
        if (array_key_exists("actions", $options)) {
            $this->setActions($options["actions"]);
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

        $this->setStartWrapper("");
        $this->setEndWrapper("");
        $this->setActions(array());

        return $this;
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set start wrapper.
     *
     * @param string $startWrapper
     *
     * @return $this
     */
    public function setStartWrapper($startWrapper)
    {
        $this->startWrapper = $startWrapper;

        return $this;
    }

    /**
     * Get start wrapper.
     *
     * @return string
     */
    public function getStartWrapper()
    {
        return $this->startWrapper;
    }

    /**
     * Set end wrapper.
     *
     * @param string $endWrapper
     *
     * @return $this
     */
    public function setEndWrapper($endWrapper)
    {
        $this->endWrapper = $endWrapper;

        return $this;
    }

    /**
     * Get end wrapper.
     *
     * @return string
     */
    public function getEndWrapper()
    {
        return $this->endWrapper;
    }

    /**
     * Set actions.
     *
     * @param array $actions
     *
     * @return $this
     */
    public function setActions(array $actions)
    {
        foreach ($actions as $action) {
            $newAction = new Action();
            $this->actions[] = $newAction->setOptions($action);
        }

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
}
