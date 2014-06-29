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

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
                    new InputArgument("entity", InputArgument::REQUIRED, "The Doctrine entity class name (shortcut notation).")
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = Validators::validateEntityName($input->getArgument("entity"));
        list($bundle, $entity) = $this->parseShortcutNotation($entity);

        $entityClass = $this->getContainer()->get("doctrine")->getAliasNamespace($bundle) . "\\" . $entity;
        $metadata = $this->getEntityMetadata($entityClass);
        $bundle = $this->getApplication()->getKernel()->getBundle($bundle);
        $generator = $this->getGenerator($bundle);

        $generator->generate($bundle, $entity, $metadata[0]);

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