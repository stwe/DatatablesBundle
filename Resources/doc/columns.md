# Columns

1. [Column](#1-column)
2. [Array Column](#2-array-column)
3. [Virtual Column](#3-virtual-column)
4. [Boolean Column](#4-boolean-column)
5. [DateTime Column](#5-datetime-column)
6. [Timeago Column](#6-timeago-column)
7. [Action Column](#7-action-column)
8. [Multiselect Column](#8-multiselect-column)
9. [Image Column](#9-image-column)
10. [Gallery Column](#10-gallery-column)
11. [ProgressBar Column](#11-progress-bar-column)
12. [Make your own](#12-make-your-own)

## 1. Column

Represents the most basic column, including many-to-one and one-to-one relations.

### Default template

SgDatatablesBundle:Column:column.html.twig

### Options

| Option               | Type           | Default                |
|----------------------|----------------|------------------------|
| class                | string         | ''                     |
| padding              | string         | ''                     |
| name                 | string         | ''                     |
| orderable            | boolean        | true                   |
| render               | null or string | null                   |
| searchable           | boolean        | true                   |
| title                | string         | ''                     |
| type                 | string         | ''                     |
| visible              | boolean        | true                   |
| width                | string         | ''                     |
| filter               | array          | array('text', array()) |
| default              | string         | ''                     |
| editable             | boolean        | false                  |
| editable_role        | null or string | null                   |

### Example

``` php
$this->columnBuilder
    ->add('title', 'column', array(
        'title' => 'title',
        'searchable' => false,
        'orderable' => false,
        'default' => 'default title value'
    ))
;
```

For many-to-one associations:

``` php
$this->columnBuilder
    ->add('createdBy.username', 'column', array(
        'title' => 'CreatedBy'
    ))
    ->add('updatedBy.username', 'column', array(
        'title' => 'UpdatedBy'
    ))
;
```
___

## 2. Array column

Represents a column for many-to-many or one-to-many associations.

### Default template

SgDatatablesBundle:Column:array.html.twig

### Options

| Option               | Type           | Default                |          |
|----------------------|----------------|------------------------|----------|
| class                | string         | ''                     |          |
| padding              | string         | ''                     |          |
| name                 | string         | ''                     |          |
| orderable            | boolean        | true                   |          |
| render               | null or string | null                   |          |
| searchable           | boolean        | true                   |          |
| title                | string         | ''                     |          |
| type                 | string         | ''                     |          |
| visible              | boolean        | true                   |          |
| width                | string         | ''                     |          |
| filter               | array          | array('text', array()) |          |
| default              | string         | ''                     |          |
| data                 | string         |                        | required |
| count                | boolean        | false                  |          |
| count_action         | array          | array()                |          |

### Count Action options

| Option           | Type             | Default                      |          |
|------------------|------------------|------------------------------|----------|
| route            | string           |                              | required |
| route_parameters | array            | array()                      |          |
| icon             | string           | ''                           |          |
| label            | string           | ''                           |          |
| confirm          | boolean          | false                        |          |
| confirm_message  | string           | 'datatables.bulk.confirmMsg' |          |
| attributes       | array            | array()                      |          |
| role             | string           | ''                           |          |
| render_if        | Closure or array | array()                      |          |

### Example

``` php
$this->columnBuilder
    ->add('posts.title', 'array', array(
            'title' => 'Posts',
            'data' => 'posts[, ].title' // required option
        ))
        // count example:
        ->add('posts.comments.title', 'array', array(
            'title' => 'Posts comments',
            'count' => true,
            'data' => 'posts[, ].comments[, ].title',
            'count_action' => array(
                'route' => 'post_show',
                'route_parameters' => array(
                    'id' => 'id' // the post id
                ),
                //'label' => 'Comments',
                //'icon' => 'fa fa-eye-slash',
                'attributes' => array(
                    'rel' => 'tooltip',
                    'title' => 'Comments',
                    'class' => 'badge alert-info',
                    //'class' => 'btn btn-primary btn-xs',
                    'role' => 'button'
                ),
            )
        ))
;
```
___

## 3. Virtual column

Represents a virtual column.

### Default template

SgDatatablesBundle:Column:column.html.twig

### Options

see: Column

### Example

``` php
$this->columnBuilder
    ->add('a virtual field', 'virtual', array(
        'title' => 'virtual'
    ))
;
```
___

## 4. Boolean column

Represents a boolean column.

### Default template

SgDatatablesBundle:Column:boolean.html.twig

### Options

| Option               | Type           | Default               |
|----------------------|----------------|-----------------------|
| class                | string         | ''                    |
| padding              | string         | ''                    |
| name                 | string         | ''                    |
| orderable            | boolean        | true                  |
| render               | null or string | 'render_boolean'      |
| searchable           | boolean        | true                  |
| title                | string         | ''                    |
| type                 | string         | ''                    |
| visible              | boolean        | true                  |
| width                | string         | ''                    |
| true_icon            | string         | ''                    |
| false_icon           | string         | ''                    |
| true_label           | string         | ''                    |
| false_label          | string         | ''                    |
| filter               | array          | see the below example |
| editable             | boolean        | false                 |
| editable_role        | null or string | null                  |

### Example

``` php
$this->columnBuilder
    ->add('visible', 'boolean', array(
        'title' => 'Visible',
        'true_icon' => 'glyphicon glyphicon-ok',
        'false_icon' => 'glyphicon glyphicon-remove',
        'true_label' => 'yes',
        'false_label' => 'no',
        'filter' => array('select', array(
            'search_type' => 'eq',
            'select_options' => array('' => 'Any', '1' => 'Yes', '0' => 'No')
        )),
    ))
;
```
___

## 5. DateTime column

Represents a datetime column.

### Default template

SgDatatablesBundle:Column:datetime.html.twig

### Options

| Option               | Type           | Default                |
|----------------------|----------------|------------------------|
| class                | string         | ''                     |
| padding              | string         | ''                     |
| name                 | string         | ''                     |
| orderable            | boolean        | true                   |
| render               | null or string | 'render_datetime'      |
| searchable           | boolean        | true                   |
| title                | string         | ''                     |
| type                 | string         | ''                     |
| visible              | boolean        | true                   |
| width                | string         | ''                     |
| filter               | array          | array('text', array()) |
| date_format          | string         | 'lll'                  |
| editable             | boolean        | false                  |
| editable_role        | null or string | null                   |

### Example

``` php
$this->columnBuilder
    ->add('createdAt', 'datetime', array(
        'title' => 'Created',
        'date_format' => 'LLL', // default = "lll"
        'filter' => array('daterange', array()),
    ))
;
```
___

## 6. Timeago column

Represents a timeago column.

### Default template

SgDatatablesBundle:Column:timeago.html.twig

### Options

| Option               | Type           | Default                |
|----------------------|----------------|------------------------|
| class                | string         | ''                     |
| padding              | string         | ''                     |
| name                 | string         | ''                     |
| orderable            | boolean        | true                   |
| render               | null or string | 'render_timeago'       |
| searchable           | boolean        | true                   |
| title                | string         | ''                     |
| type                 | string         | ''                     |
| visible              | boolean        | true                   |
| width                | string         | ''                     |
| filter               | array          | array('text', array()) |

### Example

``` php
$this->columnBuilder
    ->add('createdAt', 'timeago', array(
        'title' => 'Created'
    ))
;
```
___

## 7. Action column

Represents an action column.

### Default template

SgDatatablesBundle:Column:action.html.twig

### Options

| Option     | Type        | Default |          |
|------------|-------------|---------|----------|
| class      | string      | ''      |          |
| padding    | string      | ''      |          |
| name       | string      | ''      |          |
| title      | string      | ''      |          |
| type       | string      | ''      |          |
| visible    | boolean     | true    |          |
| width      | string      | ''      |          |
| start_html | string      | ''      |          |
| end_html   | string      | ''      |          |
| actions    | array       |         | required |

### Action options

| Option           | Type             | Default                      |          |
|------------------|------------------|------------------------------|----------|
| route            | string           |                              | required |
| route_parameters | array            | array()                      |          |
| icon             | string           | ''                           |          |
| label            | string           | ''                           |          |
| confirm          | boolean          | false                        |          |
| confirm_message  | string           | 'datatables.bulk.confirmMsg' |          |
| attributes       | array            | array()                      |          |
| role             | string           | ''                           |          |
| render_if        | Closure or array | array()                      |          |

### Example

``` php
$this->columnBuilder
    ->add(null, 'action', array(
        'title' => 'Actions',
        'start_html' => '<div class="wrapper_example_class">',
        'end_html' => '</div>',
        'actions' => array( // required option
            array(
                'route' => 'post_edit',
                'route_parameters' => array(
                    'id' => 'id'
                ),
                'icon' => 'glyphicon glyphicon-edit',
                'attributes' => array(
                    'rel' => 'tooltip',
                    'title' => 'Edit',
                    'class' => 'btn btn-primary btn-xs',
                    'role' => 'button'
                ),
                'confirm' => true,
                'confirm_message' => 'Are you sure?',
                'role' => 'ROLE_ADMIN',
                'render_if' => function($rowEntity) {
                    /**
                     * Return a boolean variable or boolean condition
                     * $rowEntity['columnName'] 
                     */
                    return ($rowEntity['title'] === 'Title 1');
                },
            ),
            array(
                'route' => 'post_show',
                'route_parameters' => array(
                    'id' => 'id'
                ),
                'label' => 'Show',
                'attributes' => array(
                    'rel' => 'tooltip',
                    'title' => 'Show',
                    'class' => 'btn btn-default btn-xs',
                    'role' => 'button'
                ),
                'role' => 'ROLE_USER',
                'render_if' => array(
                    'id' => 1,
                    'username' => 'admin',
                    'enabled' => false,
                    // ...
                )
            )
        )
    ))
;
```

### Render_if

The render_if option can be used with a Closure or array.

```php
'render_if' => array(
    'id' => 1,
    'username' => 'admin',
    'enabled' => false,
    // ...
)
```

```php
'render_if' => function($rowEntity) {
    /**
     * Return a boolean variable or boolean condition
     * $rowEntity['columnName'] 
     */
    return ($rowEntity['title'] === 'Title 1');
},
```
___

## 8. Multiselect column

### Default template

SgDatatablesBundle:Column:multiselect.html.twig

### Options

| Option     | Type        | Default |          |
|------------|-------------|---------|----------|
| class      | string      | ''      |          |
| padding    | string      | ''      |          |
| name       | string      | ''      |          |
| title      | string      | ''      |          |
| type       | string      | ''      |          |
| visible    | boolean     | true    |          |
| width      | string      | ''      |          |
| start_html | string      | ''      |          |
| end_html   | string      | ''      |          |
| actions    | array       |         | required |
| attributes | array       | array() |          |
| value      | string      | 'id'    |          |

### Multiselect-Action options

| Option           | Type        | Default |          |
|------------------|-------------|---------|----------|
| route            | string      |         | required |
| route_parameters | array       | array() |          |
| icon             | string      | ''      |          |
| label            | string      | ''      |          |
| attributes       | array       | array() |          |
| role             | string      | ''      |          |

### Example

#### Datatables class

``` php
$this->getColumnBuilder()
    ->add(null, 'multiselect', array(
        'start_html' => '<div class="wrapper" id="testwrapper">',
        'end_html' => '</div>',
        'attributes' => array(
            'class' => 'testclass',
            'name' => 'testname',
        ),
        'actions' => array(
            array(
                'route' => 'post_bulk_delete',
                'label' => 'Delete',
                'role' => 'ROLE_ADMIN',
                'icon' => 'fa fa-times',
                'attributes' => array(
                    'rel' => 'tooltip',
                    'title' => 'Delete',
                    'class' => 'btn btn-primary btn-xs',
                    'role' => 'button'
                ),
            ),
            array(
                'route' => 'post_bulk_disable',
                'label' => 'Disable',
                'icon' => 'fa fa-lock'
            )
        )
    ))
;
```

#### Controller

```php
/**
 * Bulk delete action.
 *
 * @param Request $request
 *
 * @Route("/bulk/delete", name="post_bulk_delete")
 * @Method("POST")
 *
 * @return Response
 */
public function bulkDeleteAction(Request $request)
{
    $isAjax = $request->isXmlHttpRequest();

    if ($isAjax) {
        $choices = $request->request->get('data');
        $token = $request->request->get('token');

        if (!$this->isCsrfTokenValid('multiselect', $token)) {
            throw new AccessDeniedException('The CSRF token is invalid.');
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Post');

        foreach ($choices as $choice) {
            $entity = $repository->find($choice['value']);
            $em->remove($entity);
        }

        $em->flush();

        return new Response('Success', 200);
    }

    return new Response('Bad Request', 400);
}
```
___

## 9. Image column

Shows an uploaded image.

Example entity:

```php
/**
 * Class Post
 *
 * @ORM\Table()
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Post
{
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
    
    // ...
}
```

### Default template

SgDatatablesBundle:Column:image.html.twig

### Options

| Option                   | Type           | Default                |          |
|--------------------------|----------------|------------------------|----------|
| class                    | string         | ''                     |          |
| padding                  | string         | ''                     |          |
| name                     | string         | ''                     |          |
| orderable                | boolean        | false                  |          |
| searchable               | boolean        | false                  |          |
| title                    | string         | ''                     |          |
| type                     | string         | ''                     |          |
| visible                  | boolean        | true                   |          |
| width                    | string         | ''                     |          |
| filter                   | array          | array('text', array()) |          |
| imagine_filter           | string         | ''                     |          |
| imagine_filter_enlarged  | null or string | null                   |          |
| relative_path            | string         |                        | required |
| holder_url               | string         | ''                     |          |
| holder_width             | string         | '50'                   |          |
| holder_height            | string         | '50'                   |          |
| enlarge                  | boolean        | false                  |          |

### Example

``` php
$this->columnBuilder
    ->add('imageName', 'image', array(
        'title' => 'Bild',
        'relative_path' => 'images/posts',
        //'imagine_filter' => 'my_thumb_40x40',
        //'imagine_filter_enlarged' => 'my_thumb_500x500',
        //'holder_url' => 'https://placehold.it',
        //'holder_width' => '65',
        //'holder_height' => '65',
        //'enlarge' => true
    ))
;
```
___

## 10. Gallery column

This column shows a list of uploaded images.

Example: Suppose you have an entity `Post`, and `Post` have one or more images associated.

```php
/**
 * Class Post
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Post
{
    // ...

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Media", mappedBy="post", cascade={"persist"})
     */
    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }
    
    // ...
}
```

```php
/**
 * Class Media
 *
 * @ORM\Table()
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Media
{
    // ...

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
    
    // ...
}
```

### Default template

SgDatatablesBundle:Column:image.html.twig

### Options

| Option                  | Type           | Default                |          |
|-------------------------|----------------|------------------------|----------|
| class                   | string         | ''                     |          |
| padding                 | string         | ''                     |          |
| name                    | string         | ''                     |          |
| orderable               | boolean        | false                  |          |
| searchable              | boolean        | false                  |          |
| title                   | string         | ''                     |          |
| type                    | string         | ''                     |          |
| visible                 | boolean        | true                   |          |
| width                   | string         | ''                     |          |
| filter                  | array          | array('text', array()) |          |
| imagine_filter          | string         |                        | required |
| imagine_filter_enlarged | null or string | null                   |          |
| relative_path           | string         |                        | required |
| holder_url              | string         | ''                     |          |
| holder_width            | string         | '50'                   |          |
| holder_height           | string         | '50'                   |          |
| enlarge                 | boolean        | false                  |          |
| view_limit              | integer        | 4                      |          |

### Example

``` php
$this->columnBuilder
    ->add('images.fileName', 'gallery', array(
        'title' => 'Bilder',
        'relative_path' => 'images/posts',
        'imagine_filter' => 'my_thumb_40x40',
        //'holder_url' => 'https://placehold.it',
        //'holder_width' => '65',
        //'holder_height' => '65',
        'enlarge' => true,
        'view_limit' => 2,
    ))
;
```

## 11. Progress Bar column

Progress bars. Bootstrap 3 is recommended.

### Default template

SgDatatablesBundle:Column:progress_bar.html.twig

### Options

| Option               | Type           | Default               |
|----------------------|----------------|-----------------------|
| class                | string         | ''                    |
| padding              | string         | ''                    |
| name                 | string         | ''                    |
| orderable            | boolean        | true                  |
| render               | null or string | render_progress_bar   |
| searchable           | boolean        | true                  |
| title                | string         | ''                    |
| type                 | string         | ''                    |
| visible              | boolean        | true                  |
| width                | string         | ''                    |
| filter               | array          | see the below example |
| bar_classes          | string         | ''                    |
| value_min            | string         | '0'                   |
| value_max            | string         | '100'                 |
| label                | boolean        | true                  |
| multi_color          | boolean        | false                 |

### Example

```php
$this->columnBuilder
    ->add('value', 'progress_bar', array(
        'title' => 'My value',
        'label' => true,
        'filter' => array('text', array(
            'search_type' => 'eq'
        )),
        'value_min' => '0',
        'value_max' => '10',
        'multi_color' => true
        //'bar_classes' => 'progress-bar-success' // see: http://getbootstrap.com/components/#progress
    ))
;
```


## 12. Make your own

In some case, you'll need to create new Column to fit your custom needs.
To do so, you'll simply have to create a class extending the `Sg\DatatablesBundle\Datatable\Column\AbstractColumn` and
use it in the Datatable class:

```php
    ...
    $this->columnBuilder
        ->add('title', new MyOwnColumn(), [...])
        ->add('client.name', new MyOtherOwnColumn(), [...])
    ...
```
.

- Define `getTemplate` to talk to datatables api : (ex. `SgDatatablesBundle:Column:column.html.twig`)
- Implement the `renderContent` function to render complexe contents
