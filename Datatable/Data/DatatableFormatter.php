<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Data;

/**
 * Class DatatableFormatter
 *
 * @package Sg\DatatablesBundle\Datatable\Data
 */
class DatatableFormatter
{
    /**
     * @var DatatableQuery
     */
    private $datatableQuery;

    /**
     * @var array
     */
    private $output;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    public function __construct(DatatableQuery $datatableQuery)
    {
        $this->datatableQuery = $datatableQuery;
        $this->output = array('data' => array());
    }

    //-------------------------------------------------
    // Formatter
    //-------------------------------------------------

    public function runFormatter()
    {
        $columns = $this->datatableQuery->getColumns();
        $paginator = $this->datatableQuery->getPaginator();
        $lineFormatter = $this->datatableQuery->getLineFormatter();

        foreach ($paginator as $row) {

            // 1. Call the the lineFormatter to format row items
            if (is_callable($lineFormatter)) {
                $row = call_user_func($lineFormatter, $row);
            }

            foreach ($columns as $column) {
                // 2. Add some special data to the output array. For example, the visibility of actions.
                $column->addDataToOutputArray($row);
                // 3. Call columns renderContent method to format row items (e.g. for images)
                $column->renderContent($row, $this->datatableQuery);
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
