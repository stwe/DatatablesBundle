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
     * Specifies the request type (GET or POST).
     * Default: 'GET'
     *
     * @var string
     */
    private $type;

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
     * Response constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->setType('GET');
        $this->datatable = null;
        $this->datatableQueryBuilder = null;
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
        $type = strtoupper($type);

        if ('GET' === $type || 'POST' === $type) {
            $this->type = $type;
        } else {
            throw new Exception("Response::setType(): unsupported type $type");
        }

        return $this;
    }

    /**
     * Get datatable.
     *
     * @return null|DatatableInterface
     */
    public function getDatatable()
    {
        return $this->datatable;
    }

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
            throw new Exception('DatatableResponse::getResponse(): Set a Datatable class with setDatatable().');
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
            'recordsTotal' => true === $countAllResults ? (int) $this->datatableQueryBuilder->getCountAllResults() : 0,
            'recordsFiltered' => (int) $this->datatableQueryBuilder->getCountFilteredResults()
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

        if ('GET' === strtoupper($this->getType())) {
            $parameterBag = $this->request->query;
        }

        if ('POST' === strtoupper($this->getType())) {
            $parameterBag = $this->request->request;
        }

        return $parameterBag->all();
    }
}
