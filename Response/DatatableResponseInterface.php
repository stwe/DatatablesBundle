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
use Sg\DatatablesBundle\Datatable\Column\ColumnInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;

/**
 * Interface DatatableResponseInterface
 *
 * @package Sg\DatatablesBundle\Response
 */
interface DatatableResponseInterface
{
    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set datatable.
     *
     * @param DatatableInterface $datatable
     *
     * @return $this
     * @throws Exception
     */
    public function setDatatable(DatatableInterface $datatable);

    /**
     * Get DatatableQueryBuilder instance.
     *
     * @return DatatableQueryBuilder
     */
    public function getDatatableQueryBuilder();

    //-------------------------------------------------
    // Response
    //-------------------------------------------------

    /**
     * Get response.
     *
     * @param bool $countAllResults
     * @param bool $outputWalkers
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getResponse($countAllResults = true, $outputWalkers = false);
}
