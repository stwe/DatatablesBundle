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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCommand;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Exception;

/**
 * Class CreateDatatableCommand
 *
 * @package Sg\DatatablesBundle\Command
 */
class CreateDatatableCommand extends GenerateDoctrineCommand
{
    //-------------------------------------------------
    // The 'sg:datatable:generate' Command
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('sg:datatable:generate')
            ->setAliases(array('sg:datatables:generate', 'sg:generate:datatable', 'sg:generate:datatables'))
            ->setDescription('Generates a new Datatable based on a Doctrine entity.')
            ->addArgument('entity', InputArgument::REQUIRED, 'The entity class name (shortcut notation).')
            ->addOption('fields', 'f', InputOption::VALUE_OPTIONAL, 'The fields to create columns in the new Datatable.')
            ->addOption('overwrite', 'o', InputOption::VALUE_NONE, 'Allow to overwrite an existing Datatable.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // get entity argument
        $entity = Validators::validateEntityName($input->getArgument('entity'));
        list($bundle, $entity) = $this->parseShortcutNotation($entity);

        // get entity's metadata
        $entityClass = $this->getContainer()->get('doctrine')->getAliasNamespace($bundle)."\\".$entity;
        $metadata = $this->getEntityMetadata($entityClass);

        // get fields option
        $fieldsOption = $input->getOption('fields');
        null === $fieldsOption ? $fields = $this->getFieldsFromMetadata($metadata[0]) : $fields = $this->parseFields($fieldsOption);

        // get overwrite option
        $overwrite = $input->getOption('overwrite');

        // get the entity's primary key
        $id = $this->getIdentifierFromMetadata($metadata);

        // get the bundle in which to create the Datatable
        $bundle = $this->getContainer()->get('kernel')->getBundle($bundle);

        /** @var DatatableGenerator $generator */
        $generator = $this->getGenerator($bundle);
        $generator->generate($bundle, $entity, $fields, $id[0], $overwrite);

        $output->writeln(
            sprintf(
                'The new Datatable %s.php has been created under %s.',
                $generator->getClassName(),
                $generator->getClassPath()
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function createGenerator()
    {
        return new DatatableGenerator($this->getContainer()->get('filesystem'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getSkeletonDirs(BundleInterface $bundle = null)
    {
        $skeletonDirs = array();
        $skeletonDirs[] = $this->getContainer()->get('kernel')->locateResource('@SgDatatablesBundle/Resources/views/skeleton');

        return $skeletonDirs;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Parse fields.
     *
     * @param string $input
     *
     * @return array
     */
    private static function parseFields($input)
    {
        $fields = array();

        foreach (explode(' ', $input) as $value) {
            $elements = explode(':', $value);

            $row = array();
            $row['property'] = $elements[0];
            $row['column_type'] = 'Column::class';
            $row['data'] = null;
            $row['title'] = ucwords(str_replace('.', ' ', $elements[0]));

            if (isset($elements[1])) {
                switch ($elements[1]) {
                    case 'datetime':
                        $row['column_type'] = 'DateTimeColumn::class';
                        break;
                    case 'boolean':
                        $row['column_type'] = 'BooleanColumn::class';
                        break;
                }
            }

            $fields[] = $row;
        }

        return $fields;
    }

    /**
     * Get Id from metadata.
     *
     * @param array $metadata
     *
     * @return mixed
     * @throws Exception
     */
    private function getIdentifierFromMetadata(array $metadata)
    {
        if (count($metadata[0]->identifier) > 1) {
            throw new Exception('CreateDatatableCommand::getIdentifierFromMetadata(): The Datatable generator does not support entities with multiple primary keys.');
        }

        return $metadata[0]->identifier;
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

            switch ($field['type']) {
                case 'datetime':
                    $row['column_type'] = 'DateTimeColumn::class';
                    break;
                case 'boolean':
                    $row['column_type'] = 'BooleanColumn::class';
                    break;
                default:
                    $row['column_type'] = 'Column::class';
            }

            $row['title'] = ucwords($field['fieldName']);
            $row['data'] = null;
            $fields[] = $row;
        }

        foreach ($metadata->associationMappings as $relation) {
            $targetClass = $relation['targetEntity'];
            $targetMetadata = $this->getEntityMetadata($targetClass);

            foreach ($targetMetadata[0]->fieldMappings as $field) {
                $row = array();
                $row['property'] = $relation['fieldName'].'.'.$field['fieldName'];
                $row['column_type'] = 'Column::class';
                $row['title'] = ucwords(str_replace('.', ' ', $row['property']));
                if ($relation['type'] === ClassMetadataInfo::ONE_TO_MANY || $relation['type'] === ClassMetadataInfo::MANY_TO_MANY) {
                    $row['data'] = $relation['fieldName'].'[, ].'.$field['fieldName'];
                } else {
                    $row['data'] = null;
                }
                $fields[] = $row;
            }
        }

        return $fields;
    }
}
