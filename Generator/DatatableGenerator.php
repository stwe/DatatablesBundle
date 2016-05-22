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

use Sg\DatatablesBundle\Routing\DatatablesRoutingLoader;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
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
     * @param boolean         $bootstrap3 The bootstrap3 flag
     * @param boolean         $admin      The admin flag
     *
     * @throws RuntimeException
     */
    public function generate(BundleInterface $bundle, $entity, array $fields, $clientSide, $ajaxUrl, $bootstrap3, $admin)
    {
        $parts = explode("\\", $entity);
        $entityClass = array_pop($parts);
        $routePref = $entityClassLowerCase = strtolower($entityClass);

        $this->className = $entityClass . 'Datatable';
        $dirPath = $bundle->getPath() . '/Datatables';

        if (true === $admin) {
            $this->className = $entityClass . 'AdminDatatable';
            $dirPath = $bundle->getPath() . '/Datatables/Admin';
        }

        $this->classPath = $dirPath . '/' . str_replace('\\', '/', $entity) . 'Datatable.php';

        if (true === $admin) {
            $this->classPath = $dirPath . '/' . str_replace('\\', '/', $entity) . 'AdminDatatable.php';
        }

        if (file_exists($this->classPath)) {
            throw new RuntimeException(sprintf('Unable to generate the %s datatable class as it already exists under the %s file', $this->className, $this->classPath));
        }

        $parts = explode('\\', $entity);
        array_pop($parts);

        // set ajaxUrl
        if (false === $clientSide) {
            // server-side
            $this->ajaxUrl = $ajaxUrl? $ajaxUrl : $entityClassLowerCase . '_results';
        }

        if (true === $admin) {
            $routePref = DatatablesRoutingLoader::PREF . $entityClassLowerCase;
            $bootstrap3 = true;
        }

        $this->renderFile('class.php.twig', $this->classPath, array(
            'namespace' => $bundle->getNamespace(),
            'entity_namespace' => implode('\\', $parts),
            'entity_class' => $entityClass,
            'bundle' => $bundle->getName(),
            'datatable_class' => $this->className,
            'datatable_name' => $admin ? $entityClassLowerCase . '_admin_datatable' : $entityClassLowerCase . '_datatable',
            'fields' => $fields,
            'client_side' => (boolean) $clientSide,
            'ajax_url' => $admin ? DatatablesRoutingLoader::PREF . $this->ajaxUrl : $this->ajaxUrl,
            'bootstrap3' => (boolean) $bootstrap3,
            'admin' => (boolean) $admin,
            'route_pref' => $routePref
        ));
    }
}
