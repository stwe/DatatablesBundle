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

use Sg\DatatablesBundle\Datatable\View\DatatableViewInterface;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class DatatableDataManager
 *
 * @package Sg\DatatablesBundle\Datatable\Data
 */
class DatatableDataManager
{
    /**
     * The doctrine entityManager.
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $doctrine;

    /**
     * The request service.
     *
     * @var Request
     */
    private $request;

    /**
     * The serializer service.
     *
     * @var Serializer
     */
    private $serializer;

    /**
     * Holds request parameters.
     *
     * @var ParameterBag
     */
    private $parameterBag;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param EntityManger      $em        The doctrine EntityManager
     * @param Request           $request    The request service
     * @param Serializer        $serializer The serializer service
     */
    public function __construct(EntityManagerInterface $em, Request $request, Serializer $serializer)
    {
        $this->em = $em;
        $this->request = $request;
        $this->serializer = $serializer;
        $this->parameterBag = null;
    }

    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * Get Datatable.
     *
     * @param DatatableViewInterface $datatableView
     *
     * @return DatatableData
     */
    public function getDatatable(DatatableViewInterface $datatableView)
    {
        $type = $datatableView->getAjax()->getType();
        $entity = $datatableView->getEntity();

        if ("GET" === strtoupper($type)) {
            $this->parameterBag = $this->request->query;
        }

        if ("POST" === strtoupper($type)) {
            $this->parameterBag = $this->request->request;
        }

        $params = $this->parameterBag->all();

        /**
         * @var \Doctrine\ORM\Mapping\ClassMetadata $metadata
         */
        $metadata = $this->em->getClassMetadata($entity);

        $datatableQuery = new DatatableQuery($params, $metadata, $this->em, $datatableView);
        $virtualColumns = $datatableView->getColumnBuilder()->getVirtualColumns();
        $datatableData = new DatatableData($params, $metadata, $this->em, $this->serializer, $datatableQuery, $virtualColumns);
        $datatableData->setLineFormatter($datatableView->getLineFormatter());

        return $datatableData;
    }
}
