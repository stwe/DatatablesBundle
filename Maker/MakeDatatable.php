<?php

namespace App\Maker;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Doctrine\Persistence\Mapping\MappingException as CommonMappingException;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\MappingException;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Bundle\MakerBundle\Exception\RuntimeCommandException;
use Symfony\Bundle\MakerBundle\FileManager;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;

/**
 * Class MakeDatatable
 *
 */
class MakeDatatable extends AbstractMaker
{
    /** @var DoctrineHelper $doctrineHelper */
    private $doctrineHelper;

    /** @var FileManager $fileManager */
    private $fileManager;

    /** @var Generator $generator */
    private $generator;

    /** @var Inflector $inflector */
    private $inflector;

    /**
     * @param DoctrineHelper $doctrineHelper
     * @param Generator $generator
     * @param FileManager $fileManager
     */
    public function __construct(DoctrineHelper $doctrineHelper, Generator $generator, FileManager $fileManager)
    {
        $this->doctrineHelper = $doctrineHelper;
        $this->generator = $generator;
        $this->fileManager = $fileManager;
        $this->inflector = InflectorFactory::create()->build();
    }

    /**
     * Configure the command: set description, input arguments, options, etc.
     *
     * By default, all arguments will be asked interactively. If you want
     * to avoid that, use the $inputConfig->setArgumentAsNonInteractive() method.
     *
     * @param Command $command
     * @param InputConfiguration $inputConfig
     */
    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->setDescription('Generates a new Datatable based on a Doctrine entity.')
            ->addArgument(
                'entity',
                InputArgument::REQUIRED,
                'The entity class name (shortcut notation).'
            )
            ->addOption(
                'fields',
                'f',
                InputOption::VALUE_OPTIONAL,
                'The fields to create columns in the new Datatable.'
            )
            ->addOption(
                'overwrite',
                'o',
                InputOption::VALUE_NONE,
                'Allow to overwrite an existing Datatable.'
            );
    }

    /**
     * Configure any library dependencies that your maker requires.
     *
     * @param DependencyBuilder $dependencies
     */
    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }

    /**
     * Called after normal code generation: allows you to do anything.
     *
     * @param InputInterface $input
     * @param ConsoleStyle $io
     * @param Generator $generator
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $classOrNamespace = $input->getArgument('entity');

        $entityClassDetails = $this->getEntityClassDetails($classOrNamespace);
        $entityMetadata = $this->getEntityMetadata($entityClassDetails->getFullName());

        $entityVarSingular = lcfirst($this->inflector->singularize($entityClassDetails->getShortName()));

        $entityTwigVarSingular = Str::asTwigVariable($entityVarSingular);
        $datatableClassDetails = $generator->createClassNameDetails(
            $entityClassDetails->getRelativeNameWithoutSuffix(),
            'Datatables\\',
            'Datatable'
        );

        // get fields option
        $fieldsOption = $input->getOption('fields');

        if (null === $fieldsOption) {
            $fields = $this->getFieldsFromMetadata($entityMetadata[0]);
        } else {
            $fields = $this->parseFields($fieldsOption);
        }

        // get overwrite option
        $overwrite = $input->getOption('overwrite');

        $datatableClassPath = $this->fileManager->getRelativePathForFutureClass(
            $datatableClassDetails->getFullName()
        );

        $backupIndex = 0;
        do {
            $datatableClassBackupPath = $datatableClassPath . '.' . $backupIndex;
            $backupIndex++;
        } while ($this->fileManager->fileExists($datatableClassBackupPath));

        if ($overwrite) {
            $io->caution("Overwriting is enabled!");
            if ($this->fileManager->fileExists($datatableClassPath)) {
                rename($datatableClassPath, $datatableClassBackupPath);

                $io->note(
                    sprintf(
                        "Created backup %s for %s",
                        $this->fileManager->relativizePath($datatableClassBackupPath),
                        $this->fileManager->relativizePath($datatableClassPath)
                    )
                );
            }
        }

        try {
            $generator->generateClass(
                $datatableClassDetails->getFullName(),
                realpath(__DIR__ . '/../Resources/datatable/skeleton.tpl.php'),
                [
                    'entity_full_class_name' => $entityClassDetails->getFullName(),
                    'datatable_name' => $entityTwigVarSingular,
                    'fields' => $fields,
                    'route_pref' => $entityVarSingular,
                    'entity_identifier' => $entityMetadata[0]->getIdentifier(),
                ]
            );

            $generator->writeChanges();

            $this->writeSuccessMessage($io);
        } catch (\Exception $e) {
            if ($overwrite) {
                if ($this->fileManager->fileExists($datatableClassBackupPath)) {
                    if ($this->fileManager->fileExists($datatableClassPath)) {
                        unlink($datatableClassPath);
                    }

                    rename($datatableClassBackupPath, $datatableClassPath);
                }
            }

            throw $e;
        } finally {
            if ($this->fileManager->fileExists($datatableClassBackupPath)) {
                unlink($datatableClassBackupPath);
            }
        }
    }

    /**
     * Return the command name for your maker (e.g. make:report).
     *
     * @return string
     */
    public static function getCommandName() : string
    {
        return 'make:datatable';
    }

    /**
     * If necessary, you can use this method to interactively ask the user for input.
     *
     * @param InputInterface $input
     * @param ConsoleStyle $io
     * @param Command $command
     */
    public function interact(InputInterface $input, ConsoleStyle $io, Command $command)
    {
        if (null === $input->getArgument('entity')) {
            $argument = $command->getDefinition()->getArgument('entity');

            $entities = $this->doctrineHelper->getEntitiesForAutocomplete();

            $question = new Question($argument->getDescription());
            $question->setAutocompleterValues($entities);

            $value = $io->askQuestion($question);

            $input->setArgument('entity', $value);
        }
    }

    /**
     * @param string|null $className
     *
     * @return ClassNameDetails
     */
    private function getEntityClassDetails($className)
    {
        return $this->generator->createClassNameDetails(
            Validator::entityExists($className, $this->doctrineHelper->getEntitiesForAutocomplete()),
            'Entity'
        );
    }

    /**
     * @param string $classOrNamespace
     *
     * @return ClassMetadata[]
     *
     * @throws RuntimeCommandException
     */
    private function getEntityMetadata($classOrNamespace)
    {
        try {
            $metadata = $this->doctrineHelper->getMetadata($classOrNamespace);
        } catch (MappingException | CommonMappingException $mappingException) {
            $metadata = $this->doctrineHelper->getMetadata($classOrNamespace, true);
        }

        if ($metadata instanceof ClassMetadata) {
            $metadata = [$metadata];
        } elseif (class_exists($classOrNamespace)) {
            throw new RuntimeCommandException(
                sprintf(
                    'Could not find Doctrine metadata for "%s". Is it mapped as an entity?',
                    $classOrNamespace
                )
            );
        } elseif (empty($metadata)) {
            throw new RuntimeCommandException(
                sprintf(
                    'No entities were found in the "%s" namespace.',
                    $classOrNamespace
                )
            );
        }

        return $metadata;
    }

    /**
     * Returns an array of fields. Fields can be both column fields and
     * association fields.
     *
     * @param ClassMetadata $classMetadata
     *
     * @return array $fields
     */
    private function getFieldsFromMetadata(ClassMetadata $classMetadata)
    {
        $fields = [];

        foreach ($classMetadata->fieldMappings as $field) {
            $row = [];
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

        foreach ($classMetadata->associationMappings as $relation) {
            $targetClass = $relation['targetEntity'];
            $targetMetadata = $this->getEntityMetadata($targetClass);

            foreach ($targetMetadata[0]->fieldMappings as $field) {
                $row = [];
                $row['property'] = $relation['fieldName'] . '.' . $field['fieldName'];
                $row['column_type'] = 'Column::class';
                $row['title'] = ucwords(str_replace('.', ' ', $row['property']));
                // phpcs:disable Generic.Files.LineLength.MaxExceeded
                if (ClassMetadataInfo::ONE_TO_MANY === $relation['type'] || ClassMetadataInfo::MANY_TO_MANY === $relation['type']) {
                    // phpcs:enable Generic.Files.LineLength.MaxExceeded
                    $row['data'] = $relation['fieldName'] . '[, ].' . $field['fieldName'];
                } else {
                    $row['data'] = null;
                }

                $fields[] = $row;
            }
        }

        return $fields;
    }

    /**
     * Parse fields.
     *
     * @param string $input
     *
     * @return array
     */
    private function parseFields($input)
    {
        $fields = [];

        foreach (explode(' ', $input) as $value) {
            $elements = explode(':', $value);

            $row = [];
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
}