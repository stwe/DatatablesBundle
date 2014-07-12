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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCommand;
use Sensio\Bundle\GeneratorBundle\Command\Validators;

/**
 * Class GenerateDatatableCommand
 *
 * @package Sg\DatatablesBundle\Command
 */
class GenerateDatatableCommand extends GenerateDoctrineCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName("datatable:generate:class")
            ->setDescription("Generates a datatable class based on a Doctrine entity.")
            ->setDefinition(
                array(
                    new InputOption("entity", "", InputOption::VALUE_REQUIRED, "The Doctrine entity class name (shortcut notation)."),
                    new InputOption("style", "", InputOption::VALUE_REQUIRED, "The datatable style (base, base-no-classes, base-row-borders,
                        base-cell-borders, base-hover, base-order, base-stripe, jquery-ui, bootstrap, foundation)", "bootstrap"),
                    new InputOption("fields", "", InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, "The fields in the datatable"),
                    new InputOption("client-side", "", InputOption::VALUE_NONE, "The client-side flag"),
                    new InputOption("ajax-url", "", InputOption::VALUE_OPTIONAL, "The ajax url"),
                    new InputOption("filtering", "", InputOption::VALUE_OPTIONAL, "The individual filtering flag"),
                )
            )
            ->setHelp(
<<<EOT
The <info>datatable:generate:class</info> command generates a datatable class based on a Doctrine entity.
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = Validators::validateEntityName($input->getOption("entity"));
        list($bundle, $entity) = $this->parseShortcutNotation($entity);

        $style = $input->getOption("style");
        $fields = $input->getOption("fields");
        $clientSide = $input->getOption("client-side");
        $ajaxUrl = $input->getOption("ajax-url");
        $individualFiltering = $input->getOption("filtering");

        $entityClass = $this->getContainer()->get("doctrine")->getAliasNamespace($bundle) . "\\" . $entity;
        $metadata = $this->getEntityMetadata($entityClass);
        $bundle = $this->getApplication()->getKernel()->getBundle($bundle);

        $generator = $this->getGenerator($bundle);
        $generator->generate($bundle, $entity, $metadata[0], $style, $fields, $clientSide, $ajaxUrl, $individualFiltering);

        $output->writeln(
            sprintf(
                "The new %s.php class file has been created under %s.",
                $generator->getClassName(),
                $generator->getClassPath()
            )
        );
    }

    /**
     * @return DatatableGenerator
     */
    protected function createGenerator()
    {
        return new DatatableGenerator($this->getContainer()->get("filesystem"));
    }

    /**
     * @param BundleInterface $bundle
     *
     * @return array
     */
    protected function getSkeletonDirs(BundleInterface $bundle = null)
    {
        $skeletonDirs = array();

        if (isset($bundle) && is_dir($dir = $bundle->getPath() . "/Resources/SensioGeneratorBundle/skeleton")) {
            $skeletonDirs[] = $dir;
        }

        if (is_dir($dir = $this->getContainer()->get("kernel")->getRootdir() . "/Resources/SensioGeneratorBundle/skeleton")) {
            $skeletonDirs[] = $dir;
        }

        $skeletonDirs[] = __DIR__ . "/../Resources/views/skeleton";
        $skeletonDirs[] = __DIR__ . "/../Resources";

        return $skeletonDirs;
    }
}