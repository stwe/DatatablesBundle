<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Tests;

use Sg\DatatablesBundle\Tests\Datatables\PostDatatable;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManager;
use Twig_Environment;

/**
 * Class DatatableTest
 *
 * @package Sg\DatatablesBundle\Tests
 */
class DatatableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testCreate.
     */
    public function testCreate()
    {
        $tableClass = PostDatatable::class;

        /** @noinspection PhpUndefinedMethodInspection */
        $authorizationChecker = $this->getMock(AuthorizationCheckerInterface::class);
        /** @noinspection PhpUndefinedMethodInspection */
        $securityToken = $this->getMock(TokenStorageInterface::class);
        /** @noinspection PhpUndefinedMethodInspection */
        $translator = $this->getMock(TranslatorInterface::class);
        /** @noinspection PhpUndefinedMethodInspection */
        $router = $this->getMock(RouterInterface::class);
        /** @noinspection PhpUndefinedMethodInspection */
        $twig = $this->getMock(Twig_Environment::class);

        /** @noinspection PhpUndefinedMethodInspection */
        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->setMethods(
                array('getClassMetadata')
            )
            ->getMock();

        /** @noinspection PhpUndefinedMethodInspection */
        $em->expects($this->any())
            ->method('getClassMetadata')
            ->will($this->returnValue($this->getClassMetadataMock()));

        /** @var \Sg\DatatablesBundle\Tests\Datatables\PostDatatable $table */
        $table = new $tableClass($authorizationChecker, $securityToken, $translator, $router, $em, $twig);

        /** @noinspection PhpUndefinedMethodInspection */
        $this->assertEquals('post_datatable', $table->getName());

        $table->buildDatatable();
    }

    /**
     * Get classMetadataMock.
     *
     * @return mixed
     */
    public function getClassMetadataMock()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $mock = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()
            ->setMethods(array('getEntityShortName'))
            ->getMock();

        /** @noinspection PhpUndefinedMethodInspection */
        $mock->expects($this->any())
            ->method('getEntityShortName')
            ->will($this->returnValue('{entityShortName}'));

        return $mock;
    }
}
