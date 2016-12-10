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

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Twig_Environment;
use Exception;

/**
 * Class ColumnBuilder
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ColumnBuilder
{
    /**
     * The doctrine orm entity manager service.
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * The class metadata.
     *
     * @var ClassMetadata
     */
    private $metadata;

    /**
     * The Twig Environment.
     *
     * @var Twig_Environment
     */
    private $twig;

    /**
     * The generated Columns.
     *
     * @var array
     */
    private $columns;

    /**
     * This variable stores the array of column names as keys and column ids as values
     * in order to perform search column id by name.
     *
     * @var array
     */
    private $columnNames;

    /**
     * True when a unique Column was created.
     *
     * @var bool
     */
    private $uniqueFlag;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * ColumnBuilder constructor.
     *
     * @param EntityManagerInterface $em
     * @param ClassMetadata          $metadata
     * @param Twig_Environment       $twig
     */
    public function __construct(EntityManagerInterface $em, ClassMetadata $metadata, Twig_Environment $twig)
    {
        $this->em = $em;
        $this->metadata = $metadata;
        $this->twig = $twig;
        $this->columns = array();
        $this->columnNames = array();
        $this->uniqueFlag = false;
    }

    //-------------------------------------------------
    // Builder
    //-------------------------------------------------

    /**
     * Add Column.
     *
     * @param null|string            $dql
     * @param string|ColumnInterface $class
     * @param array                  $options
     *
     * @return $this
     * @throws Exception
     */
    public function add($dql, $class, array $options = array())
    {
        /**
         * @var AbstractColumn $column
         */
        $column = ColumnFactory::createColumn($class);
        // creates the empty Column Options array and the Property Accessor
        $column->initOptions(false);
        // the Column 'data' property has normally the same value as 'dql'
        $column->setDql($dql);
        $column->setData($dql);
        // inject twig for rendering special Column content
        $column->setTwig($this->twig);
        // resolve options
        $column->set($options);

        if (null === $column->getTypeOfField() && true === $column->isSelectColumn()) {
            if (true === $column->isAssociation()) {
                // @todo: set type of field for association
                $column->setTypeOfField(null);
            } else {
                $column->setTypeOfField($this->metadata->getTypeOfField($dql));
            }
        }

        if (true === $column->callAddIfClosure()) {
            $this->columns[] = $column;
            $index = count($this->columns) - 1;
            $this->columnNames[$dql] = $index;
            $column->setIndex($index);

            // Use the Column-Index as data source for Columns with 'dql' === null
            if (null === $column->getDql() && null === $column->getData()) {
                $column->setData($index);
            }
        }

        if (true === $column->isUnique()) {
            if (false === $this->uniqueFlag) {
                $this->uniqueFlag = true;
            } else {
                throw new Exception('ColumnBuilder::add(): There is only one unique Column allowed. Check the Column with index: ' . $column->getIndex() . '.');
            }
        }

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get columns.
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get columnNames.
     *
     * @return array
     */
    public function getColumnNames()
    {
        return $this->columnNames;
    }
}
