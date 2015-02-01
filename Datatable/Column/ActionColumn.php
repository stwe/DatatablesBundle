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
 * Class ActionColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ActionColumn extends AbstractColumn
{
    /**
     * Start HTML.
     *
     * @var string
     */
    protected $startWrapper;

    /**
     * End HTML.
     *
     * @var string
     */
    protected $endWrapper;

    /**
     * The actions container.
     *
     * @var array
     */
    protected $actions;


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        if (null !== $data) {
            throw new InvalidArgumentException("setData(): Null expected.");
        }

        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRender($render)
    {
        if (null !== $render) {
            throw new InvalidArgumentException("setRender(): Null expected.");
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
        $this->setOrderable(false);
        $this->setRender(null);
        $this->setSearchable(false);
        $this->setTitle("");
        $this->setType("");
        $this->setVisible(true);
        $this->setWidth("");
        $this->setStartWrapper("");
        $this->setEndWrapper("");
        $this->setActions(array());

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
        if (array_key_exists("start_html", $options)) {
            $this->setStartWrapper($options["start_html"]);
        }
        if (array_key_exists("end_html", $options)) {
            $this->setEndWrapper($options["end_html"]);
        }
        if (array_key_exists("actions", $options)) {
            $this->setActions($options["actions"]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return "SgDatatablesBundle:Column:action.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return "action";
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
