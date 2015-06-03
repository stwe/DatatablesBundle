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

use Sg\DatatablesBundle\Datatable\Column\ColumnBuilder;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Exception;

/**
 * Class AbstractDatatableView
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
abstract class AbstractDatatableView implements DatatableViewInterface
{
    /**
     * The service container.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * A Features instance.
     *
     * @var Features
     */
    protected $features;

    /**
     * An Options instance.
     *
     * @var Options
     */
    protected $options;

    /**
     * A Callbacks instance.
     *
     * @var Callbacks
     */
    protected $callbacks;

    /**
     * A ColumnBuilder instance.
     *
     * @var ColumnBuilder
     */
    protected $columnBuilder;

    /**
     * An Ajax instance.
     *
     * @var Ajax
     */
    protected $ajax;

    /**
     * Data to use as the display data for the table.
     *
     * @var mixed
     */
    protected $data;

    /**
     * The Twig templates.
     *
     * @var array
     */
    protected $templates;

    /**
     * This variable stores the array of column names as keys and column ids as values
     * in order to perform search column id by name.
     */
    private $columnNames;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param ContainerInterface $container            The service container
     * @param array              $defaultLayoutOptions The default layout options
     */
    public function __construct(ContainerInterface $container, array $defaultLayoutOptions)
    {
        $this->container = $container;

        $this->features = new Features();
        $this->options = new Options();
        $this->callbacks = new Callbacks();
        $this->columnBuilder = new ColumnBuilder();
        $this->ajax = new Ajax();

        $this->data = null;
        $this->templates = $defaultLayoutOptions["templates"];

        $this->buildDatatableView();
    }

    //-------------------------------------------------
    // DatatableViewInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function render($type = "all")
    {
        $options = array();

        if (true === $this->features->getServerSide()) {
            if ("" === $this->ajax->getUrl()) {
                throw new Exception("render(): The ajax url parameter must be given.");
            }
        } else {
            if (null === $this->data) {
                throw new Exception("render(): Call setData() in your controller.");
            } else {
                $options["view_data"] = $this->data;
            }
        }

        $options["view_features"] = $this->features;
        $options["view_options"] = $this->options;
        $options["view_callbacks"] = $this->callbacks;
        $options["view_columns"] = $this->columnBuilder->getColumns();
        $options["view_ajax"] = $this->ajax;

        $options["view_multiselect"] = $this->columnBuilder->isMultiselect();
        $options["view_multiselect_column"] = $this->columnBuilder->getMultiselectColumn();

        $options["view_table_id"] = $this->getName();

        $options["datatable"] = $this;

        switch ($type) {
            case "html":
                return $this->container->get("templating")->render($this->templates["html"], $options);
                break;
            case "js":
                return $this->container->get("templating")->render($this->templates["js"], $options);
                break;
            default:
                return $this->container->get("templating")->render($this->templates["base"], $options);
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAjax()
    {
        return $this->ajax;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnBuilder()
    {
        return $this->columnBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        return null;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set Data.
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get Data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set templates.
     *
     * @param array $templates
     *
     * @return $this
     */
    public function setTemplates(array $templates)
    {
        $this->templates = $templates;

        return $this;
    }

    /**
     * Get templates.
     *
     * @return array
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Truncate text.
     * 
     * @param string  $text
     * @param integer $chars
     * 
     * @return string
     */
    public function truncate($text, $chars = 25)
    {
        if (strlen($text) > $chars) {
            $text = substr($text . " ", 0, $chars);
            $text = substr($text, 0, strrpos($text, ' ')) . "...";
        }

        return $text;
    }

    /**
     * Searches the column index by column name.
     * Returns the position of the column in datatable columns or false if column is not found.
     *
     * @param string $name
     *
     * @return int|bool
     */
    public function getColumnIdByColumnName($name)
    {
        if (count($this->columnNames) == 0) {
            /** @var \Sg\DatatablesBundle\Datatable\Column\AbstractColumn $column */
            foreach ($this->getColumnBuilder()->getColumns() as $key => $column) {
                $this->columnNames[$column->getData()] = $key;
            }
        }

        return array_key_exists($name, $this->columnNames) ? $this->columnNames[$name] : false;
    }

    /**
     * Returns options array based on key/value pairs, where key and value are the object's properties.
     *
     * @param ArrayCollection $entitiesCollection
     * @param string          $keyPropertyName
     * @param string          $valuePropertyName
     *
     * @return array
     */
    public function getCollectionAsOptionsArray($entitiesCollection, $keyPropertyName = 'id', $valuePropertyName = 'name')
    {
        $options = [];

        foreach ($entitiesCollection as $entity) {
            $keyPropertyName = Container::camelize($keyPropertyName);
            $keyGetter = 'get' . ucfirst($keyPropertyName);
            $valuePropertyName = Container::camelize($valuePropertyName);
            $valueGetter = 'get' . ucfirst($valuePropertyName);
            $options[$entity->$keyGetter()] = $entity->$valueGetter();
        }

        return $options;
    }
}
