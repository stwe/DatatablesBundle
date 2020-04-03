<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Filter;

use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\QueryBuilder;

/**
 * Interface FilterInterface.
 */
interface FilterInterface
{
    /**
     * @return string
     */
    public function getTemplate();

    /**
     * Add an and condition.
     *
     * @param string $searchField
     * @param string $searchTypeOfField
     * @param int    $parameterCounter
     *
     * @return Andx
     */
    public function addAndExpression(Andx $andExpr, QueryBuilder $qb, $searchField, $searchValue, $searchTypeOfField, &$parameterCounter);
}
