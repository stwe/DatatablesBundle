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

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Sg\DatatablesBundle\Tests\Datatables\PostDatatable;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

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
        $authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        /** @noinspection PhpUndefinedMethodInspection */
        $securityToken = $this->createMock(TokenStorageInterface::class);
        /** @noinspection PhpUndefinedMethodInspection */
        $translator = $this->createMock(TranslatorInterface::class);
        /** @noinspection PhpUndefinedMethodInspection */
        $router = $this->createMock(RouterInterface::class);
        /** @noinspection PhpUndefinedMethodInspection */
        $twig = $this->createMock(Environment::class);
        /** @noinspection PhpUndefinedMethodInspection */
        $em = $this->createMock(EntityManager::class);

        /** @noinspection PhpUndefinedMethodInspection */
        $em->expects($this->any())
            ->method('getClassMetadata')
            ->willReturn($this->getClassMetadataMock());

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
        $mock = $this->createPartialMock(ClassMetadata::class, [
            'getEntityShortName'
        ]);

        /** @noinspection PhpUndefinedMethodInspection */
        $mock->expects($this->any())
            ->method('getEntityShortName')
            ->willReturn('{entityShortName}');

        return $mock;
    }
}
