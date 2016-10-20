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

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Class DatatableResponse
 *
 * @package Sg\DatatablesBundle\Response
 */
class DatatableResponse
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var array
     */
    private $requestParams;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $type;

    /**
     * @var DatatableQuery
     */
    private $datatableQuery;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Response constructor.
     *
     * @param RequestStack           $requestStack
     * @param EntityManagerInterface $em
     */
    public function __construct(
        RequestStack $requestStack,
        EntityManagerInterface $em
    )
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->em = $em;
        $this->setType('GET');
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return $this
     * @throws Exception
     */
    public function setType($type)
    {
        if ('GET' === strtoupper($type) || 'POST' === strtoupper($type)) {
            $this->type = $type;
        } else {
            throw new Exception("Response::setType(): unsupported type $type");
        }

        return $this;
    }

    /**
     * Get a new DatatableQuery instance.
     *
     * @return DatatableQuery
     */
    public function getDatatableQuery()
    {
        $this->requestParams = $this->getRequestParams();
        $this->datatableQuery = new DatatableQuery($this->requestParams, $this->em);

        return $this->datatableQuery;
    }

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
     */
    public function getResponse($countAllResults = true, $outputWalkers = false)
    {
        $paginator = new Paginator($this->datatableQuery->execute(), true);
        $paginator->setUseOutputWalkers($outputWalkers);

        /*
        $formatter = new DatatableFormatter($this);
        $formatter->runFormatter();

        $countAllResults = $this->datatableView->getOptions()->getCountAllResults();
        */

        $outputHeader = array(
            'draw' => (int) $this->requestParams['draw'],
            'recordsTotal' => true === $countAllResults ? (int) $this->datatableQuery->getCountAllResults() : 0,
            'recordsFiltered' => (int) $this->datatableQuery->getCountFilteredResults()
        );

        $output = array();
        foreach ($paginator as $row) {
            $output['data'][] = $row;
        }

        return new JsonResponse(array_merge($outputHeader, $output));
    }

    //-------------------------------------------------
    // Private
    //-------------------------------------------------

    /**
     * Get request params.
     *
     * @return array
     */
    private function getRequestParams()
    {
        $parameterBag = null;

        if ('GET' === strtoupper($this->getType())) {
            $parameterBag = $this->request->query;
        }

        if ('POST' === strtoupper($this->getType())) {
            $parameterBag = $this->request->request;
        }

        return $parameterBag->all();
    }
}
