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

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig_Environment;
use function class_exists;
use function class_implements;
use function gettype;
use function in_array;
use function is_string;

/**
 * Class DatatableFactory
 */
class DatatableFactory
{
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
     * The Twig Environment.
     *
     * @var Twig_Environment
     */
    protected $twig;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface         $securityToken
     * @param TranslatorInterface           $translator
     * @param RouterInterface               $router
     * @param EntityManagerInterface        $em
     * @param Twig_Environment              $twig
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $securityToken,
        TranslatorInterface $translator,
        RouterInterface $router,
        EntityManagerInterface $em,
        Twig_Environment $twig
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->securityToken        = $securityToken;
        $this->translator           = $translator;
        $this->router               = $router;
        $this->em                   = $em;
        $this->twig                 = $twig;
    }

    //-------------------------------------------------
    // Create Datatable
    //-------------------------------------------------

    /**
     * Create Datatable.
     *
     * @param string $class
     *
     * @return DatatableInterface
     *
     * @throws Exception
     */
    public function create($class)
    {
        if (! is_string($class)) {
            $type = gettype($class);
            throw new Exception("DatatableFactory::create(): String expected, $type given");
        }

        if (class_exists($class) === false) {
            throw new Exception("DatatableFactory::create(): $class does not exist");
        }

        if (in_array(DatatableInterface::class, class_implements($class))) {
            return new $class(
                $this->authorizationChecker,
                $this->securityToken,
                $this->translator,
                $this->router,
                $this->em,
                $this->twig
            );
        }

        throw new Exception("DatatableFactory::create(): The class $class should implement the DatatableInterface.");
    }
}
