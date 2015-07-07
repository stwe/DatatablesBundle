<?php

/**
 * This file is part of the WgUniversalDataTableBundle package.
 *
 * (c) stwe <https://github.com/stwe/DataTablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wg\UniversalDataTable\DataTable\Data;

use Wg\UniversalDataTable\DataTable\View\DataTableViewInterface;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

/**
 * Class DataTableDataManager
 *
 * @package Wg\UniversalDataTable\DataTable\Data
 */
class DataTableDataManager
{
    /**
     * The request.
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

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param RequestStack $requestStack
     * @param Serializer   $serializer
     */
    public function __construct(RequestStack $requestStack, Serializer $serializer)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->serializer = $serializer;

    }

    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * Get query.
     *
     * @param DataTableViewInterface $dataTableView
     * @param $disabledCoreSearch
     *
     * @return DataTableQuery
     */
    public function getQueryFrom(DataTableViewInterface $dataTableView, array $disabledCoreSearch = null)
    {
        $type = $dataTableView->getAjax()->getType();
        $parameterBag = null;

        if ('GET' === strtoupper($type)) {
            $parameterBag = $this->request->query;
        }

        if ('POST' === strtoupper($type)) {
            $parameterBag = $this->request->request;
        }

        $params = $parameterBag->all();
        $query = new DataTableQuery($this->serializer, $params, $dataTableView, $disabledCoreSearch);

        return $query;
    }
}
