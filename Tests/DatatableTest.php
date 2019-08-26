<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Tests;

use Doctrine\ORM\EntityManager;
use Sg\DatatablesBundle\Tests\Datatables\PostDatatable;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig_Environment;

/**
 * @internal
 * @coversNothing
 */
final class DatatableTest extends \PHPUnit\Framework\TestCase
{
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
        $twig = $this->createMock(Twig_Environment::class);

        /** @noinspection PhpUndefinedMethodInspection */
        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->setMethods(
                ['getClassMetadata']
            )
            ->getMock()
        ;

        // @noinspection PhpUndefinedMethodInspection
        $em->expects(static::any())
            ->method('getClassMetadata')
            ->willReturn($this->getClassMetadataMock())
        ;

        /** @var \Sg\DatatablesBundle\Tests\Datatables\PostDatatable $table */
        $table = new $tableClass($authorizationChecker, $securityToken, $translator, $router, $em, $twig);

        /** @noinspection PhpUndefinedMethodInspection */
        static::assertSame('post_datatable', $table->getName());

        $table->buildDatatable();
    }

    public function getClassMetadataMock()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $mock = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()
            ->setMethods(['getEntityShortName'])
            ->getMock()
        ;

        // @noinspection PhpUndefinedMethodInspection
        $mock->expects(static::any())
            ->method('getEntityShortName')
            ->willReturn('{entityShortName}')
        ;

        return $mock;
    }
}
