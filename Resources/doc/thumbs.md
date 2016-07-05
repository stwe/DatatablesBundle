# ImageColumn and thumbnails

## 1. Install the VichUploaderBundle

Please follow all steps as described [here](https://github.com/dustin10/VichUploaderBundle/blob/master/Resources/doc/index.md).

## 2. Install the LiipImagineBundle

Please follow all steps as described [here](http://symfony.com/doc/master/bundles/LiipImagineBundle/installation.html).

## 3: Load Featherlight

Ensure to load all js and css files from [Featherlight](https://github.com/noelboss/featherlight/) with your base layout.

## 4. Entity example

### Post entity

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Post
 *
 * @ORM\Table()
 * @ORM\Entity
 * @Vich\Uploadable
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
     * @var \datetime
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    // ...

    /**
     * @Vich\UploadableField(mapping="post_image", fileNameProperty="imageName")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $imageName;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Media", mappedBy="post", cascade={"persist"})
     */
    private $images;

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
        $this->images = new ArrayCollection();
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
     * Set updatedAt.
     *
     * @param \datetime $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    // ...

    /**
     * Set image file.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * Get image file.
     *
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set image name.
     *
     * @param string $imageName
     *
     * @return $this
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get image name.
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Add image.
     *
     * @param Media $image
     *
     * @return $this
     */
    public function addImage(Media $image)
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setPost($this);
        }

        return $this;
    }

    /**
     * Remove image.
     *
     * @param Media $image
     *
     * @return $this
     */
    public function removeImage(Media $image)
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
        }

        return $this;
    }

    /**
     * Get all images.
     *
     * @return ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }
    
    // ...
}
```

### Media entity

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Media
 *
 * @ORM\Table()
 * @ORM\Entity
 * @Vich\Uploadable
 *
 * @package AppBundle\Entity
 */
class Media
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="post_image", fileNameProperty="fileName")
     * @Assert\File(
     *     maxSize="512k",
     *     mimeTypes={"image/gif", "image/jpeg", "image/png", "image/jpg", "image/bmp"}
     * )
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="fileName", type="string", length=255)
     */
    private $fileName;

    /**
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="images")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", nullable=true)
     */
    private $post;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
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

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get image.
     *
     * @param File $image
     *
     * @return $this
     */
    public function setImage(File $image = null)
    {
        $this->image = $image;

        if ($image) {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * Get image.
     *
     * @return File
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set fileName.
     *
     * @param string $fileName
     *
     * @return $this
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName.
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set post.
     *
     * @param Post $post
     *
     * @return $this
     */
    public function setPost(Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post.
     *
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
```

## 5. PostDatatable

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
        $this->options->set(array(
            // ...
            'paging_type' => Style::FULL_NUMBERS_PAGINATION,
            'class' => Style::BOOTSTRAP_3_STYLE,
            'use_integration_options' => true
        ));

        $this->columnBuilder
            // ...

            // Image column
            ->add('imageName', 'image', array(
                'title' => 'Image',
                'relative_path' => 'images/posts',
                'imagine_filter' => 'my_thumb_40x40',
                'holder_url' => 'https://placehold.it',
                'holder_width' => '50',
                'holder_height' => '50',
                'enlarge' => true
            ))

            // Gallery column for one-to-many associations
            ->add('images.fileName', 'gallery', array(
                'title' => 'Bilder',
                'relative_path' => 'images/posts',
                'imagine_filter' => 'my_thumb_50x50',
                'imagine_filter_enlarged' => 'thumbnail_500_x_500',
                'holder_url' => 'https://placehold.it',
                'holder_width' => '50',
                'holder_height' => '50',
                'enlarge' => true
            ))
            // ...
        ;
    }

    // ...
}
```
