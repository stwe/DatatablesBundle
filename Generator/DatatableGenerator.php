<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use RuntimeException;

/**
 * Class DatatableGenerator
 *
 * @package Sg\DatatablesBundle\Generator
 */
class DatatableGenerator extends Generator
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $classPath;

    /**
     * @var string
     */
    private $style;

    /**
     * @var string
     */
    private $ajaxUrl;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param Filesystem $filesystem A Filesystem instance
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->className = "";
        $this->classPath = "";
        $this->style = "";
        $this->ajaxUrl = "";
    }


    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getClassPath()
    {
        return $this->classPath;
    }

    /**
     * Generates the datatable class if it does not exist.
     *
     * @param BundleInterface $bundle              The bundle in which to create the class
     * @param string          $entity              The entity relative class name
     * @param string          $style               The style (base, jquery-ui, bootstrap, foundation)
     * @param array           $fields              The datatable fields
     * @param string          $clientSide          The client side flag
     * @param string          $ajaxUrl             The ajax route name
     * @param string          $individualFiltering The individual filtering flag
     *
     * @throws RuntimeException
     */
    public function generate(BundleInterface $bundle, $entity, $style, array $fields, $clientSide, $ajaxUrl, $individualFiltering)
    {
        $parts = explode("\\", $entity);
        $entityClass = array_pop($parts);

        $this->className = $entityClass . "Datatable";
        $dirPath = $bundle->getPath() . "/Datatables";
        $this->classPath = $dirPath . "/" . str_replace("\\", "/", $entity) . "Datatable.php";

        if (file_exists($this->classPath)) {
            throw new RuntimeException(sprintf("Unable to generate the %s datatable class as it already exists under the %s file", $this->className, $this->classPath));
        }

        $parts = explode("\\", $entity);
        array_pop($parts);

        // set style
        $this->setStyle($style);

        // set ajaxUrl
        if (false == $clientSide) {
            // server-side
            if (!$ajaxUrl) {
                $this->ajaxUrl = strtolower($entityClass) . "_results";
            } else {
                $this->ajaxUrl = $ajaxUrl;
            }
        } else {
            // client-side
            $this->ajaxUrl = "";
        }

        $this->renderFile("class.php.twig", $this->classPath, array(
                "namespace" => $bundle->getNamespace(),
                "entity_namespace" => implode('\\', $parts),
                "entity_class" => $entityClass,
                "bundle" => $bundle->getName(),
                "datatable_class" => $this->className,
                "datatable_name" => strtolower($entityClass) . "_datatable",
                "style" => $this->style,
                "fields" => $fields,
                "client_side" => (boolean) $clientSide,
                "ajax_url" => $this->ajaxUrl,
                "individual_filtering" => (boolean) $individualFiltering
            ));
    }


    //-------------------------------------------------
    // Private
    //-------------------------------------------------

    /**
     * Sets the style format.
     *
     * @param string $style
     *
     * @return $this
     */
    private function setStyle($style)
    {
        switch ($style) {
            case "base":
                $this->style = "self::BASE_STYLE";
                break;
            case "base-no-classes":
                $this->style = "self::BASE_STYLE_NO_CLASSES";
                break;
            case "base-row-borders":
                $this->style = "self::BASE_STYLE_ROW_BORDERS";
                break;
            case "base-cell-borders":
                $this->style = "self::BASE_STYLE_CELL_BORDERS";
                break;
            case "base-hover":
                $this->style = "self::BASE_STYLE_HOVER";
                break;
            case "base-order":
                $this->style = "self::BASE_STYLE_ORDER_COLUMN";
                break;
            case "base-stripe":
                $this->style = "self::BASE_STYLE_STRIPE";
                break;
            case "jquery-ui":
                $this->style = "self::JQUERY_UI_STYLE";
                break;
            case "bootstrap":
                $this->style = "self::BOOTSTRAP_3_STYLE";
                break;
            case "foundation":
                $this->style = "self::FOUNDATION_STYLE";
                break;
            default:
                $this->style = "self::BOOTSTRAP_3_STYLE";
                break;
        }

        return $this;
    }
}