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
 * Class MultiSelectFilter
 *
 * @author benedyktbla <https://github.com/benedyktbla>
 *
 * @package Sg\DatatablesBundle\Datatable\Filter
 */
class MultiSelectFilter extends SelectFilter
{
    //-------------------------------------------------
    // FilterInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Filters:filter_multiselect.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function addAndExpression(Andx $andExpr, QueryBuilder $pivot, $searchField, $searchValue, &$i)
    {
        $orExpr = $pivot->expr()->orX();

        foreach (explode(',', $searchValue) as $searchItem) {
            $orExpr->add($this->getAndExpression($pivot->expr()->andX(), $pivot, $searchField, $searchItem, $i));
            $i++;
        }

        return $andExpr->add($orExpr);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'multiselect';
    }
}
