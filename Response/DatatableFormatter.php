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

            // 1. Call the the lineFormatter to format row items
            if (null !== $lineFormatter && is_callable($lineFormatter)) {
                $row = call_user_func($datatable->getLineFormatter(), $row);
            }

            /** @var ColumnInterface $column */
            foreach ($columns as $column) {
                // 2. Add some special data to the output array. For example, the visibility of actions.
                $column->addDataToOutputArray($row);
                // 3. Call Columns renderContent method to format row items (e.g. for images or boolean values)
                $column->renderContent($row);
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
