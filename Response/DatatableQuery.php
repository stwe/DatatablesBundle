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

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class DatatableQuery
 *
 * @package Sg\DatatablesBundle\Response
 */
class DatatableQuery
{
    /**
     * @var array
     */
    private $requestParams;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * DatatableQuery constructor.
     *
     * @param array                  $requestParams
     * @param EntityManagerInterface $em
     */
    public function __construct(
        array $requestParams,
        EntityManagerInterface $em
    )
    {
        $this->requestParams = $requestParams;
        $this->em = $em;
    }


}
