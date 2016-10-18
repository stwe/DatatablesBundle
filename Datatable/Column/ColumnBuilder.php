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
     * @var array
     */
    private $columns;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * ColumnBuilder constructor.
     *
     * @param EntityManagerInterface $em
     * @param ClassMetadata          $metadata
     */
    public function __construct(EntityManagerInterface $em, ClassMetadata $metadata)
    {
        $this->em = $em;
        $this->metadata = $metadata;
        $this->columns = array();
    }

    //-------------------------------------------------
    // Builder
    //-------------------------------------------------

    /**
     * Add Column.
     *
     * @param null|string            $data
     * @param string|ColumnInterface $class
     * @param array                  $options
     *
     * @return $this
     * @throws Exception
     */
    public function add($data, $class, array $options = array())
    {
        /**
         * @var AbstractColumn $column
         */
        $column = ColumnFactory::createColumn($class);
        $column->initOptions(false);
        $column->setData($data);
        $column->setDql($data);

        if (true === $column->isSelectColumn()) {
            if (true === $column->isAssociation()) {
                // @todo: set type of field for association
                $column->setTypeOfField(null);
            } else {
                $column->setTypeOfField($this->metadata->getTypeOfField($data));
            }
        } else {
            $column->setTypeOfField(null);
        }

        $column->set($options);

        if (true === $column->callAddIfClosure()) {
            $this->columns[] = $column;
        }

        if (true === $column->isUnique()) {
            // @todo
            // array_count_values(ex.: MultiselectColumn)
            // throw new Exception('add(): There is only one column allowed.')
        }

        return $this;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }
}
