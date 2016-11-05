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
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ClassMetadata
     */
    private $metadata;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
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
        $column->initOptions(false);
        $column->setDql($dql);
        $column->setData($dql);
        $column->setTwig($this->twig);
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
        }

        if (true === $column->isUnique()) {
            // @todo
            // array_count_values(ex.: MultiselectColumn)
            // throw new Exception('add(): There is only one column allowed.')
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
