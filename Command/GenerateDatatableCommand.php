<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Command;

use Sg\DatatablesBundle\Generator\DatatableGenerator;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Bundle\DoctrineBundle\Mapping\DisconnectedMetadataFactory;
use RuntimeException;

/**
 * Class GenerateDatatableCommand
 *
 * @package Sg\DatatablesBundle\Command
 */
class GenerateDatatableCommand extends ContainerAwareCommand
{
    /**
     * @var DatatableGenerator
     */
    private $generator;

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return (
            class_exists('Doctrine\\ORM\\Mapping\\ClassMetadataInfo')
            &&
            class_exists('Sensio\\Bundle\\GeneratorBundle\\Command\\GenerateDoctrineCommand')
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('sg:datatable:generate')
            ->setDescription('Generates a new datatable class based on the given entity.')
            ->addArgument('entity', InputArgument::REQUIRED, 'The entity class name (shortcut notation).')
            ->addOption('fields', 'f', InputOption::VALUE_OPTIONAL, 'The fields.')
            ->addOption('bootstrap3', 'b', InputOption::VALUE_NONE, 'The Bootstrap3-Framework flag.')
            ->addOption('ajax-url', 'a', InputOption::VALUE_OPTIONAL, 'The ajax url.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = Validators::validateEntityName($input->getArgument('entity'));
        list($bundle, $entity) = $this->parseShortcutNotation($entity);
        $fields = Fields::parseFields($input->getOption('fields'));
        $bootstrap = $input->getOption('bootstrap3');
        $ajaxUrl = $input->getOption('ajax-url');

        $entityClass = $this->getContainer()->get('doctrine')->getAliasNamespace($bundle) . "\\" . $entity;
        $metadata = $this->getEntityMetadata($entityClass);

        if (count($metadata[0]->identifier) > 1) {
            throw new RuntimeException('The datatable class generator does not support entities with multiple primary keys.');
        }

        $primaryKey = $metadata[0]->identifier;

        if (0 == count($fields)) {
            $fields = $this->getFieldsFromMetadata($metadata[0]);
        }

        $bundle = $this->getContainer()->get('kernel')->getBundle($bundle);

        $generator = $this->getGenerator($bundle);
        $generator->generate($bundle, $entity, $fields, $ajaxUrl, $bootstrap, $primaryKey[0]);

        $output->writeln(
            sprintf(
                'The new datatable %s.php class file has been created under %s.',
                $generator->getClassName(),
                $generator->getClassPath()
            )
        );
    }

    /**
     * Create generator.
     *
     * @return DatatableGenerator
     */
    protected function createGenerator()
    {
        return new DatatableGenerator($this->getContainer()->get('filesystem'));
    }

    /**
     * Get skeleton dirs.
     *
     * @param BundleInterface $bundle
     *
     * @return array
     */
    protected function getSkeletonDirs(BundleInterface $bundle = null)
    {
        $skeletonDirs = array();

        if (isset($bundle) && is_dir($dir = $bundle->getPath().'/Resources/SgDatatablesBundle/views/Skeleton')) {
            $skeletonDirs[] = $dir;
        }

        if (is_dir($dir = $this->getContainer()->get('kernel')->getRootdir().'/Resources/SgDatatablesBundle/views/Skeleton')) {
            $skeletonDirs[] = $dir;
        }

        $reflClass = new \ReflectionClass(get_class($this));
        $skeletonDirs[] = dirname($reflClass->getFileName()) . '/../Resources/views/Skeleton';
        $skeletonDirs[] = dirname($reflClass->getFileName()) . '/../Resources';

        return $skeletonDirs;
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
        $fields = array();

        foreach ($metadata->fieldMappings as $field) {
            $row = array();
            $row['property'] = $field['fieldName'];

            switch($field['type']) {
                case 'datetime':
                    $row['column_name'] = 'datetime';
                    break;
                case 'boolean':
                    $row['column_name'] = 'boolean';
                    break;
                default:
                    $row['column_name'] = 'column';
            }

            $row['title'] = ucwords($field['fieldName']);
            $fields[] = $row;
        }

        foreach ($metadata->associationMappings as $relation) {
            if ( ($relation['type'] !== ClassMetadataInfo::ONE_TO_MANY) && ($relation['type'] !== ClassMetadataInfo::MANY_TO_MANY) ) {

                $targetClass = $relation['targetEntity'];
                $targetMetadata = $this->getEntityMetadata($targetClass);

                foreach ($targetMetadata[0]->fieldMappings as $field) {
                    $row = array();
                    $row['property'] = $relation['fieldName'] . '.' . $field['fieldName'];
                    $row['column_name'] = 'column';
                    $row['title'] = ucwords(str_replace('.', ' ', $row['property']));
                    $fields[] = $row;
                }
            }
        }

        return $fields;
    }

    /**
     * Parse shortcut notation.
     *
     * @param $shortcut
     *
     * @return array
     */
    protected function parseShortcutNotation($shortcut)
    {
        $entity = str_replace('/', '\\', $shortcut);

        if (false === $pos = strpos($entity, ':')) {
            throw new \InvalidArgumentException(sprintf('The entity name must contain a : ("%s" given, expecting something like AcmeBlogBundle:Blog/Post)', $entity));
        }

        return array(substr($entity, 0, $pos), substr($entity, $pos + 1));
    }

    /**
     * Get entity metadata.
     *
     * @param $entity
     *
     * @return array
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    protected function getEntityMetadata($entity)
    {
        $factory = new DisconnectedMetadataFactory($this->getContainer()->get('doctrine'));

        return $factory->getClassMetadata($entity)->getMetadata();
    }

    /**
     * Get generator.
     *
     * @param BundleInterface|null $bundle
     *
     * @return mixed|DatatableGenerator
     */
    protected function getGenerator(BundleInterface $bundle = null)
    {
        if (null === $this->generator) {
            $this->generator = $this->createGenerator();
            $this->generator->setSkeletonDirs($this->getSkeletonDirs($bundle));
        }

        return $this->generator;
    }
}
