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
        $this->output = array();
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

        foreach ($paginator as $row) {

            if (null !== $lineFormatter && is_callable($lineFormatter)) {
                $row = call_user_func($datatable->getLineFormatter(), $row);
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
