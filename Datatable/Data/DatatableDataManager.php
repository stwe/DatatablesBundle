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

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Twig_Environment;

/**
 * Class DatatableDataManager
 *
 * @package Sg\DatatablesBundle\Datatable\Data
 */
class DatatableDataManager
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

    /**
     * The Twig Environment service.
     *
     * @var Twig_Environment
     */
    private $twig;

    /**
     * Configuration settings.
     *
     * @var array
     */
    private $configs;

    /**
     * True if the LiipImagineBundle is installed.
     *
     * @var boolean
     */
    private $imagineBundle;

    /**
     * True if GedmoDoctrineExtensions installed.
     *
     * @var boolean
     */
    private $doctrineExtensions;

    /**
     * The locale.
     *
     * @var string
     */
    private $locale;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param RequestStack     $requestStack
     * @param Serializer       $serializer
     * @param Twig_Environment $twig
     * @param array            $configs
     * @param array            $bundles
     */
    public function __construct(RequestStack $requestStack, Serializer $serializer, Twig_Environment $twig, array $configs, array $bundles)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->serializer = $serializer;
        $this->twig = $twig;
        $this->configs = $configs;
        $this->imagineBundle = false;
        $this->doctrineExtensions = false;

        if (true === class_exists('Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')) {
            $this->doctrineExtensions = true;
        }

        if (true === array_key_exists('LiipImagineBundle', $bundles)) {
            $this->imagineBundle = true;
        }

        $this->locale = $this->request->getLocale();
    }

    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * Get query.
     *
     * @param DatatableViewInterface $datatableView
     *
     * @return DatatableQuery
     */
    public function getQueryFrom(DatatableViewInterface $datatableView)
    {
        $type = $datatableView->getAjax()->getType();
        $parameterBag = null;

        if ('GET' === strtoupper($type)) {
            $parameterBag = $this->request->query;
        }

        if ('POST' === strtoupper($type)) {
            $parameterBag = $this->request->request;
        }

        $params = $parameterBag->all();
        $query = new DatatableQuery(
            $this->serializer,
            $params,
            $datatableView,
            $this->configs,
            $this->twig,
            $this->imagineBundle,
            $this->doctrineExtensions,
            $this->locale
        );

        return $query;
    }
}
