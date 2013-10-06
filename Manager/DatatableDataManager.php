<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Manager;

use Sg\DatatablesBundle\Datatable\DatatableData;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

/**
 * Class DatatableDataManager
 *
 * @package Sg\DatatablesBundle\Manager
 */
class DatatableDataManager
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var Logger
     */
    private $logger;


    /**
     * Ctor.
     *
     * @param RegistryInterface $doctrine   A RegistryInterface
     * @param Request           $request    A Request instance
     * @param Serializer        $serializer A Serializer instance
     * @param Logger            $logger     A Logger instance
     */
    public function __construct(RegistryInterface $doctrine, Request $request, Serializer $serializer, Logger $logger)
    {
        $this->doctrine = $doctrine;
        $this->request = $request;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * Get datatable.
     *
     * @param string $entity
     *
     * @return DatatableData A DatatableData instance
     */
    public function getDatatable($entity)
    {
        /**
         * Get all GET params.
         *
         * @var \Symfony\Component\HttpFoundation\ParameterBag $parameterBag
         */
        $parameterBag = $this->request->query;
        $params = $parameterBag->all();

        /**
         * @var \Doctrine\ORM\Mapping\ClassMetadata $metadata
         */
        $metadata = $this->doctrine->getManager()->getClassMetadata($entity);

        /**
         * @var \Doctrine\ORM\EntityManager $em
         */
        $em = $this->doctrine->getManager();

        return new DatatableData(
            $params,
            $metadata,
            $em,
            $this->serializer,
            $this->logger
        );
    }
}

