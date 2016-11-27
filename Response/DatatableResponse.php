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

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;

/**
 * Class DatatableResponse
 *
 * @package Sg\DatatablesBundle\Response
 */
class DatatableResponse
{
    /**
     * The current Request.
     *
     * @var Request
     */
    private $request;

    /**
     * $_GET or $_POST parameters.
     *
     * @var array
     */
    private $requestParams;

    /**
     * A DatatableInterface instance.
     * Default: null
     *
     * @var null|DatatableInterface
     */
    private $datatable;

    /**
     * A DatatableQueryBuilder instance.
     * This class generates a Query by given Columns.
     * Default: null
     *
     * @var null|DatatableQueryBuilder
     */
    private $datatableQueryBuilder;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * DatatableResponse constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->datatable = null;
        $this->datatableQueryBuilder = null;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set datatable.
     *
     * @param DatatableInterface $datatable
     *
     * @return $this
     */
    public function setDatatable(DatatableInterface $datatable)
    {
        $this->datatable = $datatable;

        return $this;
    }

    /**
     * Create a new DatatableQueryBuilder instance.
     *
     * @return DatatableQueryBuilder
     * @throws Exception
     */
    public function getDatatableQueryBuilder()
    {
        if (null === $this->datatable) {
            throw new Exception('DatatableResponse::getDatatableQueryBuilder(): Set a Datatable class with setDatatable().');
        }

        $this->requestParams = $this->getRequestParams();
        $this->datatableQueryBuilder = new DatatableQueryBuilder($this->requestParams, $this->datatable);

        return $this->datatableQueryBuilder;
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
     * @throws Exception
     */
    public function getResponse($countAllResults = true, $outputWalkers = false)
    {
        if (null === $this->datatable) {
            throw new Exception('DatatableResponse::getResponse(): Set a Datatable class with setDatatable().');
        }

        if (null === $this->datatableQueryBuilder) {
            throw new Exception('DatatableResponse::getResponse(): A DatatableQueryBuilder instance is needed. Call getDatatableQueryBuilder().');
        }

        $paginator = new Paginator($this->datatableQueryBuilder->execute(), true);
        $paginator->setUseOutputWalkers($outputWalkers);

        $formatter = new DatatableFormatter();
        $formatter->runFormatter($paginator, $this->datatable);

        $outputHeader = array(
            'draw' => (int) $this->requestParams['draw'],
            'recordsFiltered' => (int) $this->datatableQueryBuilder->getCountFilteredResults(),
            'recordsTotal' => true === $countAllResults ? (int) $this->datatableQueryBuilder->getCountAllResults() : 0
        );

        return new JsonResponse(array_merge($outputHeader, $formatter->getOutput()));
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
        $type = $this->datatable->getAjax()->getType();

        if ('GET' === strtoupper($type)) {
            $parameterBag = $this->request->query;
        }

        if ('POST' === strtoupper($type)) {
            $parameterBag = $this->request->request;
        }

        return $parameterBag->all();
    }
}
