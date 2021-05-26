<?php

namespace Sg\DatatablesBundle\Maker;


use Symfony\Bundle\MakerBundle\InputAwareMakerInterface;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\MakerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
Use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Bundle\TwigBundle;
use Doctrine\Bundle\DoctrineBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Console\Question\Question;
use Doctrine\ORM\Mapping\ClassMetadataInfo;


class MakeDatatable extends AbstractMaker
{
    private $doctrineHelper;
    private $baseSleleton;
    private $columnTypes = [ 'ActionColumn' => 'ActionColumn'];

    public function __construct(DoctrineHelper $doctrineHelper)
    {
        $this->doctrineHelper = $doctrineHelper;
        $this->baseSleleton = realpath(__DIR__.'/../Resources/views/skeleton');
    }


    public static function getCommandName(): string
    {
        return 'make:datatable';
    }

    /**
     * {@inheritdoc}
     */
    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->setDescription('Creates Datable for Doctrine entity class')
            ->addArgument('entity-class', InputArgument::OPTIONAL, sprintf('The class name of the entity to create CRUD (e.g. <fg=yellow>%s</>)', Str::asClassName(Str::getRandomTerm())))
            ->setHelp(file_get_contents(__DIR__.'/../Resources/help/MakeDatatable.txt'))
        ;
        $inputConfig->setArgumentAsNonInteractive('entity-class');
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command)
    {
        if (null === $input->getArgument('entity-class')) {
            $argument = $command->getDefinition()->getArgument('entity-class');
            $entities = $this->doctrineHelper->getEntitiesForAutocomplete();
            $question = new Question($argument->getDescription());
            $question->setAutocompleterValues($entities);
            $value = $io->askQuestion($question);
            $input->setArgument('entity-class', $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $entityClassDetails = $generator->createClassNameDetails(
            Validator::entityExists($input->getArgument('entity-class'), $this->doctrineHelper->getEntitiesForAutocomplete()),
            'Entity\\'
        );


        $entityDoctrineDetails = $this->doctrineHelper->createDoctrineDetails($entityClassDetails->getFullName());

        $datatableClassDetails = $generator->createClassNameDetails(
            $entityClassDetails->getRelativeNameWithoutSuffix(),
            'Datatables\\',
            'Datatable'
        );

        $className = $datatableClassDetails->getShortName();
        $entityClassLowerCase = strtolower($className);

        $metadata = $this->getEntityMetadata($entityClassDetails->getFullName());
        $fields = $this->getFieldsFromMetadata($metadata);

        sort($this->columnTypes);
        $generator->generateClass(
            $datatableClassDetails->getFullName(),
            $this->baseSleleton . '/Datatable.tpl.php',
            [
                'bounded_full_class_name' => $entityClassDetails->getFullName(),
                'bounded_class_name' => $entityClassDetails->getShortName(),
                'fields' => $fields,
                'class_name' => $className,
                'datatable_name' => $entityClassLowerCase.'_datatable',
                'route_pref' => $entityClassLowerCase,
                'column_types' => $this->columnTypes,
                'id' => $this->getIdentifierFromMetadata($metadata),
            ]
        );

        $generator->writeChanges();

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

    private function getEntityMetadata($class)
    {
        return $this->doctrineHelper->getMetadata($class, true);
    }

    /**
     * Get Id from metadata.
     *
     * @param array $metadata
     *
     * @return mixed
     * @throws Exception
     */
    private function getIdentifierFromMetadata(ClassMetadataInfo $metadata)
    {
        if (count($metadata->identifier) > 1) {
            throw new Exception('CreateDatatableCommand::getIdentifierFromMetadata(): The Datatable generator does not support entities with multiple primary keys.');
        }

        return $metadata->identifier;
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
            if(!isset($this->columnTypes[$row['column_type']])) {
                $this->columnTypes[$row['column_type']] = substr($row['column_type'], 0, -7);
            }

        }

        foreach ($metadata->associationMappings as $relation) {
            $targetClass = $relation['targetEntity'];
            $targetMetadata = $this->getEntityMetadata($targetClass, true);

            foreach ($targetMetadata->fieldMappings as $field) {
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