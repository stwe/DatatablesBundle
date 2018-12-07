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

use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Sg\DatatablesBundle\Datatable\Column\ColumnInterface;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use function array_flip;
use function array_key_exists;
use function array_merge;
use function count;
use function is_array;
use function is_int;
use function strtoupper;

/**
 * Class DatatableResponse
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
     * @var DatatableInterface|null
     */
    private $datatable;

    /**
     * A DatatableQueryBuilder instance.
     * This class generates a Query by given Columns.
     * Default: null
     *
     * @var DatatableQueryBuilder|null
     */
    private $datatableQueryBuilder;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->request               = $requestStack->getCurrentRequest();
        $this->datatable             = null;
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
     *
     * @throws Exception
     */
    public function setDatatable(DatatableInterface $datatable)
    {
        $val = $this->validateColumnsPositions($datatable);
        if (is_int($val)) {
            throw new Exception("DatatableResponse::setDatatable(): The Column with the index $val is on a not allowed position.");
        }

        $this->datatable             = $datatable;
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
        return $this->datatableQueryBuilder ? : $this->createDatatableQueryBuilder();
    }

    //-------------------------------------------------
    // Response
    //-------------------------------------------------

    /**
     * Get response.
     *
     * @param bool $countAllResults
     * @param bool $outputWalkers
     * @param bool $fetchJoinCollection
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function getResponse($countAllResults = true, $outputWalkers = false, $fetchJoinCollection = true)
    {
        if ($this->datatable === null) {
            throw new Exception('DatatableResponse::getResponse(): Set a Datatable class with setDatatable().');
        }

        if ($this->datatableQueryBuilder === null) {
            throw new Exception('DatatableResponse::getResponse(): A DatatableQueryBuilder instance is needed. Call getDatatableQueryBuilder().');
        }

        $paginator = new Paginator($this->datatableQueryBuilder->execute(), $fetchJoinCollection);
        $paginator->setUseOutputWalkers($outputWalkers);

        $formatter = new DatatableFormatter();
        $formatter->runFormatter($paginator, $this->datatable);

        $outputHeader = [
            'draw' => (int) $this->requestParams['draw'],
            'recordsFiltered' => count($paginator),
            'recordsTotal' => $countAllResults === true ? (int) $this->datatableQueryBuilder->getCountAllResults() : 0,
        ];

        return new JsonResponse(array_merge($outputHeader, $formatter->getOutput()));
    }

    //-------------------------------------------------
    // Private
    //-------------------------------------------------

    /**
     * Create a new DatatableQueryBuilder instance.
     *
     * @return DatatableQueryBuilder
     *
     * @throws Exception
     */
    private function createDatatableQueryBuilder()
    {
        if ($this->datatable === null) {
            throw new Exception('DatatableResponse::getDatatableQueryBuilder(): Set a Datatable class with setDatatable().');
        }

        $this->requestParams         = $this->getRequestParams();
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
        $type         = $this->datatable->getAjax()->getType();

        if (strtoupper($type) === 'GET') {
            $parameterBag = $this->request->query;
        }

        if (strtoupper($type) === 'POST') {
            $parameterBag = $this->request->request;
        }

        return $parameterBag->all();
    }

    /**
     * Checks Column positions.
     *
     * @param DatatableInterface $datatable
     *
     * @return int|bool
     */
    private function validateColumnsPositions(DatatableInterface $datatable)
    {
        $columns      = $datatable->getColumnBuilder()->getColumns();
        $lastPosition = count($columns);

        /** @var ColumnInterface $column */
        foreach ($columns as $column) {
            $allowedPositions = $column->allowedPositions();
            /** @noinspection PhpUndefinedMethodInspection */
            $index = $column->getIndex();
            if (! is_array($allowedPositions)) {
                continue;
            }

            $allowedPositions = array_flip($allowedPositions);
            if (array_key_exists(ColumnInterface::LAST_POSITION, $allowedPositions)) {
                $allowedPositions[$lastPosition] = $allowedPositions[ColumnInterface::LAST_POSITION];
                unset($allowedPositions[ColumnInterface::LAST_POSITION]);
            }

            if (array_key_exists($index, $allowedPositions) === false) {
                return $index;
            }
        }

        return true;
    }
}
