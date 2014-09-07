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

use Symfony\Bridge\Doctrine\RegistryInterface;
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
     * The doctrine service.
     *
     * @var RegistryInterface
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
     * @param RegistryInterface $doctrine   The doctrine service
     * @param Request           $request    The request service
     * @param Serializer        $serializer The serializer service
     */
    public function __construct(RegistryInterface $doctrine, Request $request, Serializer $serializer)
    {
        $this->doctrine = $doctrine;
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
        $metadata = $this->doctrine->getManager()->getClassMetadata($entity);

        /**
         * @var \Doctrine\ORM\EntityManager $em
         */
        $em = $this->doctrine->getManager();

        $datatableQuery = new DatatableQuery($params, $metadata, $em);
        $virtualColumns = $datatableView->getColumnBuilder()->getVirtualColumnNames();
        $datatableData = new DatatableData($params, $metadata, $em, $this->serializer, $datatableQuery, $virtualColumns);
        $datatableData->setLineFormatter($datatableView->getLineFormatter());

        return $datatableData;
    }
}