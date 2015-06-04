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
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig_Environment;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
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
     * The AuthorizationChecker service.
     *
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * The SecurityTokenStorage service.
     *
     * @var TokenStorageInterface
     */
    protected $securityToken;

    /**
     * The Twig_Environment service.
     *
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * The Translator service.
     *
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * The Router service.
     *
     * @var RouterInterface
     */
    protected $router;

    /**
     * The Doctrine service.
     *
     * @var RegistryInterface
     */
    protected $doctrine;

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
     *
     * @var array
     */
    private $columnNames;

    /**
     * A custom query.
     *
     * @var QueryBuilder
     */
    protected $qb;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface         $securityToken
     * @param Twig_Environment              $twig
     * @param TranslatorInterface           $translator
     * @param RouterInterface               $router
     * @param RegistryInterface             $doctrine
     * @param array                         $defaultLayoutOptions
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $securityToken,
        Twig_Environment $twig,
        TranslatorInterface $translator,
        RouterInterface $router,
        RegistryInterface $doctrine,
        array $defaultLayoutOptions
    )
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->securityToken = $securityToken;
        $this->twig = $twig;
        $this->translator = $translator;
        $this->router = $router;
        $this->doctrine = $doctrine;

        $this->features = new Features();
        $this->options = new Options();
        $this->columnBuilder = new ColumnBuilder();
        $this->ajax = new Ajax();

        $this->data = null;
        $this->templates = $defaultLayoutOptions["templates"];

        $this->qb = null;

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
        $options["view_columns"] = $this->columnBuilder->getColumns();
        $options["view_ajax"] = $this->ajax;

        $options["view_multiselect"] = $this->columnBuilder->isMultiselect();
        $options["view_multiselect_column"] = $this->columnBuilder->getMultiselectColumn();

        $options["view_table_id"] = $this->getName();

        $options["datatable"] = $this;

        switch ($type) {
            case "html":
                return $this->twig->render($this->templates["html"], $options);
                break;
            case "js":
                return $this->twig->render($this->templates["js"], $options);
                break;
            default:
                return $this->twig->render($this->templates["base"], $options);
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

    /**
     * {@inheritdoc}
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * {@inheritdoc}
     */
    public function getQb()
    {
        return $this->qb;
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
