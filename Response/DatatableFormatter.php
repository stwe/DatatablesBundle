<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Response;

use Sg\DatatablesBundle\Datatable\Column\ColumnInterface;
use Sg\DatatablesBundle\Datatable\DatatableInterface;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class DatatableFormatter
 *
 * @package Sg\DatatablesBundle\Response
 */
class DatatableFormatter
{
    /**
     * The output array.
     *
     * @var array
     */
    private $output;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * DatatableFormatter constructor.
     */
    public function __construct()
    {
        $this->output = array('data' => array());
    }

    //-------------------------------------------------
    // Formatter
    //-------------------------------------------------

    /**
     * Create the output array.
     *
     * @param Paginator          $paginator
     * @param DatatableInterface $datatable
     */
    public function runFormatter(Paginator $paginator, DatatableInterface $datatable)
    {
        $lineFormatter = $datatable->getLineFormatter();
        $columns = $datatable->getColumns();

        foreach ($paginator as $row) {

            // 1. Set (if necessary) the custom data source for the Columns with a 'data' option
            foreach ($columns as $column) {
                /** @noinspection PhpUndefinedMethodInspection */
                $dql = $column->getDql();
                /** @noinspection PhpUndefinedMethodInspection */
                $data = $column->getData();

                /** @noinspection PhpUndefinedMethodInspection */
                if (false === $column->isAssociation()) {
                    if (null !== $dql && $dql !== $data && false === array_key_exists($data, $row)) {
                        $row[$data] = $row[$dql];
                        unset($row[$dql]);
                    }
                }
            }

            // 2. Call the the lineFormatter to format row items
            if (null !== $lineFormatter && is_callable($lineFormatter)) {
                $row = call_user_func($datatable->getLineFormatter(), $row);
            }

            /** @var ColumnInterface $column */
            foreach ($columns as $column) {
                // 3. Add some special data to the output array. For example, the visibility of actions.
                $column->addDataToOutputArray($row);
                // 4. Call Columns renderContent method to format row items (e.g. for images or boolean values)
                $column->renderCellContent($row);
            }

            $this->output['data'][] = $row;
        }
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get output.
     *
     * @return array
     */
    public function getOutput()
    {
        return $this->output;
    }
}
