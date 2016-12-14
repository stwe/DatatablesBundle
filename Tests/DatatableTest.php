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

/**
 * Class DatatableTest
 *
 * @package Sg\DatatablesBundle\Tests
 */
class DatatableTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $tableClass = 'Sg\DatatablesBundle\Tests\Datatables\PostDatatable';

        $authorizationChecker = $this->getMockBuilder('Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface')
            ->getMock();

        $securityToken = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface')
            ->getMock();

        $translator = $this->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')
            ->getMock();

        $router = $this->getMockBuilder('Symfony\Component\Routing\RouterInterface')
            ->getMock();

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')
            ->getMock();

        /** @var \Sg\DatatablesBundle\Tests\Datatables\PostDatatable $table */
        $table = new $tableClass($authorizationChecker, $securityToken, $translator, $router, $em);

        $this->assertEquals('post_datatable', $table->getName());

        $table->buildDatatable();
    }
}
