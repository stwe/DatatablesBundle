<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Response;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Sg\DatatablesBundle\Datatable\Column\ColumnInterface;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

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
     * Default: null.
     *
     * @var DatatableInterface|null
     */
    private $datatable;

    /**
     * A DatatableQueryBuilder instance.
     * This class generates a Query by given Columns.
     * Default: null.
     *
     * @var DatatableQueryBuilder|null
     */
    private $datatableQueryBuilder;

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
     * @throws Exception
     *
     * @return $this
     */
    public function setDatatable(DatatableInterface $datatable)
    {
        $val = $this->validateColumnsPositions($datatable);
        if (\is_int($val)) {
            throw new Exception("DatatableResponse::setDatatable(): The Column with the index {$val} is on a not allowed position.");
        }

        $this->datatable = $datatable;
        $this->datatableQueryBuilder = null;

        return $this;
    }

    /**
     * Get DatatableQueryBuilder instance.
     *
     * @return DatatableQueryBuilder
     */
    public function getDatatableQueryBuilder()
    {
        return $this->datatableQueryBuilder ?: $this->createDatatableQueryBuilder();
    }

    //-------------------------------------------------
    // Response
    //-------------------------------------------------

    /**
     * Get response data as array.
     *
     * @param bool $countAllResults
     * @param bool $outputWalkers
     * @param bool $fetchJoinCollection
     *
     * @throws Exception
     *
     * @return array
     */
    public function getData($countAllResults = true, $outputWalkers = false, $fetchJoinCollection = true)
    {
        if (null === $this->datatable) {
            throw new Exception('DatatableResponse::getResponse(): Set a Datatable class with setDatatable().');
        }

        if (null === $this->datatableQueryBuilder) {
            throw new Exception('DatatableResponse::getResponse(): A DatatableQueryBuilder instance is needed. Call getDatatableQueryBuilder().');
        }

        $paginator = new Paginator($this->datatableQueryBuilder->execute(), $fetchJoinCollection);
        $paginator->setUseOutputWalkers($outputWalkers);

        $formatter = new DatatableFormatter();
        $formatter->runFormatter($paginator, $this->datatable);

        $outputHeader = [
            'draw' => (int) $this->requestParams['draw'],
            'recordsFiltered' => \count($paginator),
            'recordsTotal' => true === $countAllResults ? (int) $this->datatableQueryBuilder->getCountAllResults() : 0,
        ];

        return array_merge($outputHeader, $formatter->getOutput());
    }

    /**
     * @param bool $countAllResults
     * @param bool $outputWalkers
     * @param bool $fetchJoinCollection
     *
     * @throws Exception
     *
     * @return JsonResponse
     */
    public function getResponse($countAllResults = true, $outputWalkers = false, $fetchJoinCollection = true)
    {
        return new JsonResponse($this->getData($countAllResults, $outputWalkers, $fetchJoinCollection));
    }

    //-------------------------------------------------
    // Private
    //-------------------------------------------------

    /**
     * Create a new DatatableQueryBuilder instance.
     *
     * @throws Exception
     *
     * @return DatatableQueryBuilder
     */
    private function createDatatableQueryBuilder()
    {
        if (null === $this->datatable) {
            throw new Exception('DatatableResponse::getDatatableQueryBuilder(): Set a Datatable class with setDatatable().');
        }

        $this->requestParams = $this->getRequestParams();
        $this->datatableQueryBuilder = new DatatableQueryBuilder($this->requestParams, $this->datatable);

        return $this->datatableQueryBuilder;
    }

    /**
     * Get request params.
     *
     * @return array
     */
    private function getRequestParams()
    {
        $parameterBag = null;
        $type = $this->datatable->getAjax()->getMethod();

        if ('GET' === strtoupper($type)) {
            $parameterBag = $this->request->query;
        }

        if ('POST' === strtoupper($type)) {
            $parameterBag = $this->request->request;
        }

        return $parameterBag->all();
    }

    /**
     * Checks Column positions.
     *
     * @return bool|int
     */
    private function validateColumnsPositions(DatatableInterface $datatable)
    {
        $columns = $datatable->getColumnBuilder()->getColumns();
        $lastPosition = \count($columns);

        /** @var ColumnInterface $column */
        foreach ($columns as $column) {
            $allowedPositions = $column->allowedPositions();
            /** @noinspection PhpUndefinedMethodInspection */
            $index = $column->getIndex();
            if (\is_array($allowedPositions)) {
                $allowedPositions = array_flip($allowedPositions);
                if (\array_key_exists(ColumnInterface::LAST_POSITION, $allowedPositions)) {
                    $allowedPositions[$lastPosition] = $allowedPositions[ColumnInterface::LAST_POSITION];
                    unset($allowedPositions[ColumnInterface::LAST_POSITION]);
                }

                if (false === \array_key_exists($index, $allowedPositions)) {
                    return $index;
                }
            }
        }

        return true;
    }
}
