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
        // NOTICE: we need this to properly test in PHP v5.3
        $self = $this;

        $classMetadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()
            ->getMock();

        $classMetadata
            ->method('getIdentifierFieldNames')
            ->willReturn(array());

        $driver = $this->getMockBuilder('Doctrine\DBAL\Driver')
            ->getMock();

        $connection = $this->getMockBuilder('Doctrine\DBAL\Connection')
            ->disableOriginalConstructor()
            ->getMock();

        $connection
            ->method('getDriver')
            ->willReturn($driver);

        $configuration = $this->getMockBuilder('Doctrine\ORM\Configuration')
            ->getMock();

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')
            ->getMock();

        $emInstance = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $emInstance
            ->method('getExpressionBuilder')
            ->willReturnCallback(function () {
                return new \Doctrine\ORM\Query\Expr();
            });

        $emInstance
            ->method('createQuery')
            ->willReturnCallback(function () use ($self, $emInstance) {
                $query = new \Doctrine\ORM\Query($emInstance);
                $self->setPrivateProperty($query, '_hints', array());

                if (!empty($dql)) {
                    $query->setDql($dql);
                }

                return $query;
            });

        $emInstance
            ->method('getConfiguration')
            ->willReturn($configuration);

        $emInstance
            ->method('getConnection')
            ->willReturn($connection);

        $queryBuilder = new \Doctrine\ORM\QueryBuilder($emInstance);

        $em
            ->method('getClassMetadata')
            ->willReturn($classMetadata);

        $em
            ->method('getConnection')
            ->willReturn($connection);

        $em
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
     * @param bool   $recursive Merge array content recursive/deeply.
     */
    protected function extendPrivateArrayProperty($object, $propertyName, array $value, $recursive = false)
    {
        $data = (array)$this->getPrivateProperty($object, $propertyName);

        if ($recursive === false) {
            $data = array_merge($data, $value);
        } else {
            $data = array_merge_recursive($data, $value);
        }

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