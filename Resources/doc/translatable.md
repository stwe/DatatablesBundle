# Integrate the Translatable behavior extension for Doctrine 2

## 1. Install the StofDoctrineExtensionsBundle

The [StofDoctrineExtensionsBundle](https://github.com/stof/StofDoctrineExtensionsBundle) allows to easily use [DoctrineExtensions](https://github.com/Atlantic18/DoctrineExtensions) in your Symfony project. 

Please follow all steps as described [here](https://github.com/stof/StofDoctrineExtensionsBundle/blob/master/Resources/doc/index.rst).

Don't forget to activate the Translatable behavior in your `config.yml`:

```yml
# StofDoctrineExtensionsBundle
stof_doctrine_extensions:
    default_locale: "%locale%"
    translation_fallback: true
    orm:
        default:
            translatable: true
```

## 2. Translatable Entity example

### The Post entity

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Post
 *
 * @ORM\Table()
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="PostTranslation")
 *
 * @package AppBundle\Entity
 */
class Post
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255)
     */
    private $title;
    
    // ...

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PostTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    private $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Get id.
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    // ...

    /**
     * Get translations.
     *
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Add translation.
     *
     * @param PostTranslation $translation
     *
     * @return $this
     */
    public function addTranslation(PostTranslation $translation)
    {
        if (!$this->translations->contains($translation)) {
            $this->translations[] = $translation;
            $translation->setObject($this);
        }

        return $this;
    }
}
```

### The translation entity for Post

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="post_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class PostTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;

    /**
     * Convenient constructor
     *
     * @param string $locale
     * @param string $field
     * @param string $value
     */
    public function __construct($locale, $field, $value)
    {
        $this->setLocale($locale);
        $this->setField($field);
        $this->setContent($value);
    }
}
```

## 3. Setup for the SgDatatablesBundle

### Config.yml

```yml
# config.yml

sg_datatables:
    query:
        translation_query_hints: true
```

### PostDatatable

```php
<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class PostDatatable
 *
 * @package AppBundle\Datatables
 */
class PostDatatable extends AbstractDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        // ..

        // add _locale to the route
        $this->ajax->set(array(
            'url' => $this->router->generate('post_results', array("_locale" => $options['locale'])),
            'type' => 'GET'
        ));
        
        // ...

        $this->columnBuilder
            ->add('id', 'column', array(
                'title' => 'Id',
            ))
            ->add('title', 'column', array(
                'title' => 'Title',
            ))
            
            // ...
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\Post';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'post_datatable';
    }
}
```

### Controller actions

```php
<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Post controller.
 *
 * @Route("/post")
 */
class PostController extends Controller
{
    /**
     * Lists all Post entities.
     *
     * @Route("/{_locale}/", name="post", defaults={"_locale": "en"}, requirements={"_locale": "en|de|it"})
     * @Method("GET")
     * @Template(":post:index.html.twig")
     */
    public function indexAction($_locale)
    {
        $options = array(
            'locale' => $_locale
        );
    
        $datatable = $this->get('app.datatable.post');
        $datatable->buildDatatable($options);

        return array(
            'datatable' => $datatable,
        );
    }

    /**
     * Get all Post entities.
     *
     * @Route("/results/{_locale}/", name="post_results", defaults={"_locale": "en"}, requirements={"_locale": "en|de|it"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexResultsAction($_locale)
    {
        $options = array(
            'locale' => $_locale
        );
    
        $datatable = $this->get('app.datatable.post');
        $datatable->buildDatatable($options);

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        return $query->getResponse();
    }
}
```
