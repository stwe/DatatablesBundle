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
use Doctrine\ORM\Mapping\ClassMetadataInfo;

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
     * Ctor.
     *
     * @param Filesystem $filesystem A Filesystem instance
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

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
     * Generates the entity form class if it does not exist.
     *
     * @param BundleInterface   $bundle   The bundle in which to create the class
     * @param string            $entity   The entity relative class name
     * @param ClassMetadataInfo $metadata The entity metadata class
     *
     * @throws \RuntimeException
     */
    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata)
    {
        $parts = explode("\\", $entity);
        $entityClass = array_pop($parts);

        $this->className = $entityClass . "Datatable";
        $dirPath = $bundle->getPath() . "/Datatables";
        $this->classPath = $dirPath . "/" . str_replace("\\", "/", $entity) . "Datatable.php";

        if (file_exists($this->classPath)) {
            throw new \RuntimeException(sprintf("Unable to generate the %s datatable class as it already exists under the %s file", $this->className, $this->classPath));
        }

        if (count($metadata->identifier) > 1) {
            throw new \RuntimeException("The datatable generator does not support entity classes with multiple primary keys.");
        }

        $parts = explode("\\", $entity);
        array_pop($parts);

        $this->renderFile("class.php.twig", $this->classPath, array(
                "fields" => $this->getFieldsFromMetadata($metadata),
                "namespace" => $bundle->getNamespace(),
                "entity_namespace" => implode('\\', $parts),
                "entity_class" => $entityClass,
                "bundle" => $bundle->getName(),
                "datatable_class" => $this->className,
                "datatable_name" => strtolower($entityClass) . "_datatable",
                "ajax_url" => strtolower($entityClass) . "_results"
            ));
    }

    /**
     * Returns an array of fields. Fields can be both column fields and
     * association fields.
     *
     * @param ClassMetadataInfo $metadata
     *
     * @return array $fields
     */
    private function getFieldsFromMetadata(ClassMetadataInfo $metadata)
    {
        $fields = (array) $metadata->fieldNames;

        // Remove the primary key field if it's not managed manually
        if (!$metadata->isIdentifierNatural()) {
            $fields = array_diff($fields, $metadata->identifier);
        }

        foreach ($metadata->associationMappings as $fieldName => $relation) {
            if ($relation['type'] !== ClassMetadataInfo::ONE_TO_MANY) {
                $fields[] = $fieldName;
            }
        }

        return $fields;
    }

    protected function render($template, $parameters)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem("%kernel.root_dir%/../src/Sg/DatatablesBundle/Resources/views/Skeleton/"), array(
            'debug' => true,
            'cache' => false,
            'strict_variables' => true,
            'autoescape' => false,
        ));

        return $twig->render($template, $parameters);
    }
}