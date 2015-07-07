<?php

/**
 * This file is part of the WgUniversalDataTableBundle package.
 *
 * (c) stwe <https://github.com/stwe/DataTablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wg\UniversalDataTable\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use RuntimeException;

/**
 * Class DataTableGenerator
 *
 * @package Wg\UniversalDataTable\Generator
 */
class DataTableGenerator extends Generator
{
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
    private $ajaxUrl;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->className = '';
        $this->classPath = '';
        $this->ajaxUrl = '';
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
     * @param BundleInterface $bundle     The bundle in which to create the class
     * @param string          $entity     The entity relative class name
     * @param array           $fields     The datatable fields
     * @param boolean         $clientSide The client side flag
     * @param string          $ajaxUrl    The ajax url
     *
     * @throws RuntimeException
     */
    public function generate(BundleInterface $bundle, $entity, array $fields, $clientSide, $ajaxUrl)
    {
        $parts = explode("\\", $entity);
        $entityClass = array_pop($parts);

        $this->className = $entityClass . 'DataTable';
        $dirPath = $bundle->getPath() . '/DataTables';
        $this->classPath = $dirPath . '/' . str_replace('\\', '/', $entity) . 'DataTable.php';

        if (file_exists($this->classPath)) {
            throw new RuntimeException(sprintf('Unable to generate the %s datatable class as it already exists under the %s file', $this->className, $this->classPath));
        }

        $parts = explode('\\', $entity);
        array_pop($parts);

        // set ajaxUrl
        if (false === $clientSide) {
            // server-side
            if (!$ajaxUrl) {
                $this->ajaxUrl = strtolower($entityClass) . '_results';
            } else {
                $this->ajaxUrl = $ajaxUrl;
            }
        }

        $this->renderFile('class.php.twig', $this->classPath, array(
            'namespace' => $bundle->getNamespace(),
            'entity_namespace' => implode('\\', $parts),
            'entity_class' => $entityClass,
            'bundle' => $bundle->getName(),
            'datatable_class' => $this->className,
            'datatable_name' => strtolower($entityClass) . '_datatable',
            'fields' => $fields,
            'client_side' => (boolean) $clientSide,
            'ajax_url' => $this->ajaxUrl
        ));
    }
}
