<?php

/*
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
use Sg\DatatablesBundle\Datatable\Column\ColumnBuilder;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface as LegacyTranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

abstract class AbstractDatatable implements DatatableInterface
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
     * @var Environment
     */
    protected $twig;

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

    /**
     * A Features instance.
     *
     * @var Features
     */
    protected $features;

    /**
     * A Callbacks instance.
     *
     * @var Callbacks
     */
    protected $callbacks;

    /**
     * A Events instance.
     *
     * @var Events
     */
    protected $events;

    /**
     * An Extensions instance.
     *
     * @var Extensions
     */
    protected $extensions;

    /**
     * A Language instance.
     *
     * @var Language
     */
    protected $language;

    /**
     * The unique id for this instance.
     *
     * @var int
     */
    protected $uniqueId;

    /**
     * The PropertyAccessor.
     *
     * @var PropertyAccessor
     */
    protected $accessor;

    //-------------------------------------------------

    /**
     * Used to generate unique names.
     *
     * @var array
     */
    protected static $uniqueCounter = [];

    /**
     * @throws Exception
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $securityToken,
        $translator,
        RouterInterface $router,
        EntityManagerInterface $em,
        Environment $twig
    ) {
        $this->validateName();

        if (isset(self::$uniqueCounter[$this->getName()])) {
            $this->uniqueId = ++self::$uniqueCounter[$this->getName()];
        } else {
            $this->uniqueId = self::$uniqueCounter[$this->getName()] = 1;
        }

        $this->authorizationChecker = $authorizationChecker;
        $this->securityToken = $securityToken;

        if (!($translator instanceof LegacyTranslatorInterface) && !($translator instanceof TranslatorInterface)) {
            throw new \InvalidArgumentException(sprintf('The $translator argument of %s must be an instance of %s or %s, a %s was given.', static::class, LegacyTranslatorInterface::class, TranslatorInterface::class, get_class($translator)));
        }
        $this->translator = $translator;
        $this->router = $router;
        $this->em = $em;
        $this->twig = $twig;

        $metadata = $em->getClassMetadata($this->getEntity());
        $this->columnBuilder = new ColumnBuilder($metadata, $twig, $router, $this->getName(), $em);

        $this->ajax = new Ajax();
        $this->options = new Options();
        $this->features = new Features();
        $this->callbacks = new Callbacks();
        $this->events = new Events();
        $this->extensions = new Extensions();
        $this->language = new Language();

        $this->accessor = PropertyAccess::createPropertyAccessor();
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
    public function getColumnBuilder()
    {
        return $this->columnBuilder;
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
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * {@inheritdoc}
     */
    public function getCallbacks()
    {
        return $this->callbacks;
    }

    /**
     * {@inheritdoc}
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * {@inheritdoc}
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionsArrayFromEntities($entities, $keyFrom = 'id', $valueFrom = 'name')
    {
        $options = [];

        foreach ($entities as $entity) {
            if (true === $this->accessor->isReadable($entity, $keyFrom) && true === $this->accessor->isReadable($entity, $valueFrom)) {
                $options[$this->accessor->getValue($entity, $keyFrom)] = $this->accessor->getValue($entity, $valueFrom);
            }
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getUniqueId()
    {
        return $this->uniqueId;
    }

    /**
     * {@inheritdoc}
     */
    public function getUniqueName()
    {
        return $this->getName().($this->getUniqueId() > 1 ? '-'.$this->getUniqueId() : '');
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
