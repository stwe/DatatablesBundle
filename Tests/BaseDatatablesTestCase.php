<?php

namespace Sg\DatatablesBundle\Tests;

/**
 * Class BaseDatatablesTestCase
 *
 * @package Sg\DatatablesBundle\Tests
 */
class BaseDatatablesTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $tableClass
     *
     * @return Datatables\PostDatatable
     */
    protected function createDummyDatatable($tableClass = 'Sg\DatatablesBundle\Tests\Datatables\PostDatatable')
    {
        $authorizationChecker = $this->getMockBuilder('Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface')
            ->getMock();

        $securityToken = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface')
            ->getMock();

        $translator = $this->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')
            ->getMock();

        $router = $this->getMockBuilder('Symfony\Component\Routing\RouterInterface')
            ->getMock();

        $em = $this->getEntityManagerMock();

        /** @var \Sg\DatatablesBundle\Tests\Datatables\PostDatatable $table */
        $table = new $tableClass($authorizationChecker, $securityToken, $translator, $router, $em);

        return $table;
    }

    /**
     * @return \Doctrine\ORM\EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getEntityManagerMock()
    {
        $classMetadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()
            ->getMock();

        $classMetadata
            ->expects($this->any())
            ->method('getIdentifierFieldNames')
            ->willReturn(array());

        $metadataFactory = $this->getMockBuilder('Doctrine\Common\Persistence\Mapping\ClassMetadataFactory')
            ->getMock();

        $metadataFactory
            ->expects($this->any())
            ->method('getMetadataFor')
            ->willReturn($classMetadata);

        $driver = $this->getMockBuilder('Doctrine\DBAL\Driver')
            ->getMock();

        $connection = $this->getMockBuilder('Doctrine\DBAL\Connection')
            ->disableOriginalConstructor()
            ->getMock();

        $connection
            ->expects($this->any())
            ->method('getDriver')
            ->willReturn($driver);

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')
            ->getMock();

        $queryBuilder = new \Doctrine\ORM\QueryBuilder($em);

        $em
            ->expects($this->any())
            ->method('getMetadataFactory')
            ->willReturn($metadataFactory);

        $em
            ->expects($this->any())
            ->method('getConnection')
            ->willReturn($connection);

        $em
            ->expects($this->any())
            ->method('createQueryBuilder')
            ->willReturn($queryBuilder);

        return $em;
    }


    //-------------------------------------------------
    // Helper methods to access private properties.
    //-------------------------------------------------

    /**
     * @param object $object
     * @param string $propertyName
     *
     * @return \ReflectionMethod
     */
    protected function getPrivateProperty($object, $propertyName)
    {
        $reflector = new \ReflectionClass($object);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * @param object $object
     * @param string $propertyName
     * @param mixed  $propertyValue
     */
    protected function setPrivateProperty($object, $propertyName, $propertyValue)
    {
        $reflector = new \ReflectionClass($object);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $propertyValue);
    }

    /**
     * @param object $object
     * @param string $propertyName
     * @param array  $value
     */
    protected function extendPrivateArrayProperty($object, $propertyName, array $value)
    {
        $data = (array)$this->getPrivateProperty($object, $propertyName);
        $data = array_merge_recursive($data, $value);
        $this->setPrivateProperty($object, $propertyName, $data);
    }

    /**
     * @param object|string $classNameOrObject
     * @param string        $methodName
     *
     * @return \ReflectionMethod
     */
    protected function getPrivateMethod($classNameOrObject, $methodName)
    {
        $reflector = new \ReflectionClass($classNameOrObject);
        $method = $reflector->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @param object $object
     * @param string $methodName
     * @param array  $args
     *
     * @return mixed
     */
    protected function invokePrivateMethod($object, $methodName, array $args = array())
    {
        return $this
            ->getPrivateMethod($object, $methodName)
            ->invokeArgs($object, $args);
    }
}