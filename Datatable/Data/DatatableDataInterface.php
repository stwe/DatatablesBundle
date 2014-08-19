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
 * Interface DatatableDataInterface
 *
 * @package Sg\DatatablesBundle\Datatable\Data
 */
interface DatatableDataInterface
{
    /**
     * Get Response.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse();

    /**
     * Add a callback function.
     *
     * @param string $callback
     *
     * @throws \Exception
     * @return self
     */
    public function addWhereBuilderCallback($callback);
}