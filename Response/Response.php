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
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Class Response
 *
 * @package Sg\DatatablesBundle\Response
 */
class Response
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $type;

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

    //-------------------------------------------------
    // Response
    //-------------------------------------------------

    /**
     * Get jsonResponse.
     */
    public function getQuery()
    {
        $requestParams = $this->getRequestParams();

        $query = new DatatableQuery(
            $requestParams,
            $this->em
        );

        return $query;
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
