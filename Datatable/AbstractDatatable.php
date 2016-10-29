<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable;

use Sg\DatatablesBundle\Datatable\Column\ColumnBuilder;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Class AbstractDatatable
 *
 * @package Sg\DatatablesBundle\Datatable
 */
abstract class AbstractDatatable implements DatatableInterface
{
    const NAME_REGEX = '/[a-zA-Z0-9\-\_]+/';

    /**
     * The AuthorizationChecker service.
     *
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * The SecurityTokenStorage service.
     *
     * @var TokenStorageInterface
     */
    protected $securityToken;

    /**
     * The Translator service.
     *
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * The Router service.
     *
     * @var RouterInterface
     */
    protected $router;

    /**
     * The doctrine orm entity manager service.
     *
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * A ColumnBuilder instance.
     *
     * @var ColumnBuilder
     */
    protected $columnBuilder;

    /**
     * An Ajax instance.
     *
     * @var Ajax
     */
    protected $ajax;

    /**
     * An Options instance.
     *
     * @var Options
     */
    protected $options;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * AbstractDatatable constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface         $securityToken
     * @param TranslatorInterface           $translator
     * @param RouterInterface               $router
     * @param EntityManagerInterface        $em
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $securityToken,
        TranslatorInterface $translator,
        RouterInterface $router,
        EntityManagerInterface $em
    )
    {
        $this->validateName();

        $this->authorizationChecker = $authorizationChecker;
        $this->securityToken = $securityToken;
        $this->translator = $translator;
        $this->router = $router;
        $this->em = $em;

        $metadata = $em->getClassMetadata($this->getEntity());
        $this->columnBuilder = new ColumnBuilder($em, $metadata);

        $this->ajax = new Ajax();
        $this->options = new Options();
    }

    //-------------------------------------------------
    // DatatableInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return $this->columnBuilder->getColumns();
    }

    /**
     * {@inheritdoc}
     */
    public function getAjax()
    {
        return $this->ajax;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    //-------------------------------------------------
    // Private
    //-------------------------------------------------

    /**
     * Checks the name only contains letters, numbers, underscores or dashes.
     *
     * @throws Exception
     */
    private function validateName()
    {
        if (1 !== preg_match(self::NAME_REGEX, $this->getName())) {
            throw new Exception('AbstractDatatable::validateName(): The result of the getName method can only contain letters, numbers, underscore and dashes.');
        }
    }
}
