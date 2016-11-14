<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Filter;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Andx;

/**
 * Class TextFilter
 *
 * @package Sg\DatatablesBundle\Datatable\Filter
 */
class TextFilter extends AbstractFilter
{
    //-------------------------------------------------
    // FilterInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:filter:input.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function addAndExpression(Andx $andExpr, QueryBuilder $qb, $searchField, $searchValue, &$parameterCounter)
    {
        $andExpr = $this->getAndExpression($andExpr, $qb, $searchField, $searchValue, $parameterCounter);
        $parameterCounter++;

        return $andExpr;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Returns the type for the <input> element.
     *
     * @return string
     */
    public function getType()
    {
        return 'text';
    }
}
