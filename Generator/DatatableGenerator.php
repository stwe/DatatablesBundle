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
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Exception;

/**
 * Class DatatableGenerator
 *
 * @package Sg\DatatablesBundle\Generator
 */
class DatatableGenerator extends Generator
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $classPath;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * DatatableGenerator constructor.
     */
    public function __construct()
    {
        $this->className = '';
        $this->classPath = '';
    }

    //-------------------------------------------------
    // Generator
    //-------------------------------------------------

    /**
     * Generates a new Datatable if it does not exist.
     *
     * @param BundleInterface $bundle    The bundle in which to create the Datatable
     * @param string          $entity    The entity class name
     * @param array           $fields    The fields to create columns in the new Datatable.
     * @param mixed           $id        The entity's primary key.
     * @param array           $overwrite Allow to overwrite an existing Datatable.
     *
     * @throws Exception
     */
    public function generate(BundleInterface $bundle, $entity, array $fields, $id, $overwrite)
    {
        $parts = explode("\\", $entity);
        $entityClass = array_pop($parts);
        $entityClassLowerCase = strtolower($entityClass);

        $this->className = $entityClass.'Datatable';
        $dirPath = $bundle->getPath().'/Datatables';
        $this->classPath = $dirPath.'/'.str_replace('\\', '/', $entity).'Datatable.php';

        if (!$overwrite) {
            if (file_exists($this->classPath)) {
                throw new Exception(
                    sprintf(
                        'Unable to generate the %s as it already exists under the %s.',
                        $this->className,
                        $this->classPath
                    )
                );
            }
        }

        $parts = explode('\\', $entity);
        array_pop($parts);

        $this->renderFile('datatable.php.twig', $this->classPath, array(
            'namespace' => $bundle->getNamespace(),
            'entity_namespace' => implode('\\', $parts),
            'entity_class' => $entityClass,
            'bundle' => $bundle->getName(),
            'datatable_class' => $this->className,
            'datatable_name' => $entityClassLowerCase.'_datatable',
            'fields' => $fields,
            'route_pref' => $entityClassLowerCase,
            'id' => $id,
        ));
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get class name.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Get class path.
     *
     * @return string
     */
    public function getClassPath()
    {
        return $this->classPath;
    }
}
