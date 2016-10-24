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

| Option          | Type           | Default                | Required | Description |
|-----------------|----------------|------------------------|----------|-------------|
| class           | string         | ''                     |          | Class to assign to each cell in the column. |
| default_content | null or string | null                   |          | Default content for a field that can have a null or undefined value. |
| padding         | string         | ''                     |          | Add padding to the text content used when calculating the optimal width for a table. |
| name            | string         | ''                     |          | Set a descriptive name for a column. |
| orderable       | boolean        | true                   |          | Enable or disable ordering on this column. |
| render          | null or string | null                   |          | This property will modify the data that is used by DataTables. |
| searchable      | boolean        | true                   |          | Enable or disable filtering on the data in this column. |
| title           | string         | ''                     |          | The displayed column title. This value is not translated automatically. |
| type            | string         | ''                     |          | Set the column type - used for filtering and sorting string processing. |
| visible         | boolean        | true                   |          | Display or hide this column completly so no data will be displayed in the browser. |
| width           | string         | ''                     |          | Adjust the column's width. This value will set the element attribute `style="width: XXXpx;`. |
| order_sequence  | null or array  | null                   |          | Control the default ordering direction. |
| filter          | array          | array('text', array()) |          | See [Filtering](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/filter.md#2-serverside-individual-column-filtering) for more information. |
| add_if          | Closure        | null                   |          | Add a closure to check e.g. the current user's permissions and display a column depending on that result. |
| default         | string         | ''                     |          | Default content for a field that can have an empty string. |
| editable        | boolean        | false                  |          | Enable edit mode for this column. |
| editable_if     | Closure        | null                   |          | Enable edit mode for this column depending on a closure condition e.g. permissions. |

### Example

``` php
$this->columnBuilder
    ->add('title', 'column', array(
        'title' => 'title',
        'searchable' => false,
        'orderable' => false,
        'default' => 'default title value',
        'add_if' => function() {
            return ($this->authorizationChecker->isGranted('ROLE_ADMIN'));
        },
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

For Doctrine Embeddables:

``` php
$this->columnBuilder
    ->add('credentials\\.username', 'column', array(
        'title' => 'Username'
    ))
;
```
___

## 2. Array column

Represents a column for many-to-many or one-to-many associations.

### Default template

SgDatatablesBundle:Column:array.html.twig

### Options

| Option               | Type           | Default                | Required | Description |
|----------------------|----------------|------------------------|----------|-------------|
| class                | string         | ''                     |          | Class to assign to each cell in the column. |
| default_content      | null or string | null                   |          | Default content for a field that can have a null or undefined value. |
| padding              | string         | ''                     |          | Add padding to the text content used when calculating the optimal width for a table. |
| name                 | string         | ''                     |          | Set a descriptive name for a column. |
| orderable            | boolean        | true                   |          | Enable or disable ordering on this column. |
| render               | null or string | null                   |          | This property will modify the data that is used by DataTables. |
| searchable           | boolean        | true                   |          | Enable or disable filtering on the data in this column. |
| title                | string         | ''                     |          | The displayed column title. This value is not translated automatically. |
| type                 | string         | ''                     |          | Set the column type - used for filtering and sorting string processing. |
| visible              | boolean        | true                   |          | Display or hide this column completly so no data will be displayed in the browser. |
| width                | string         | ''                     |          | Adjust the column's width. This value will set the element attribute `style="width: XXXpx;`. |
| order_sequence       | null or array  | null                   |          | Control the default ordering direction. |
| filter               | array          | array('text', array()) |          | See [Filtering](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/filter.md#2-serverside-individual-column-filtering) for more information. |
| add_if               | Closure        | null                   |          | Add a closure to check e.g. the current user's permissions and display a column depending on that result. |
| default              | string         | ''                     |          | Default content for a field that can have an empty string. |
| data                 | string         |                        | ✔        | Fetch array data from this entity attribute. You can define sub elements e.g. "foo.bar".|
| count                | boolean        | false                  |          | Count array elements. |
| count_action         | array          | array()                |          | The counter represents a link, see [Count Action options](#count-action-options). |

### Count Action options

| Option           | Type    | Default                      | Required | Description |
|------------------|---------|------------------------------|----------|-------------|
| route            | string  |                              | ✔        | Redirect user to this route ... |
| route_parameters | array   | array()                      |          | ... using the following optional route parameters. |
| icon             | string  | ''                           |          | Display an icon e.g. `glyphicon glyphicon-new-window`. |
| label            | string  | ''                           |          | Optional label text to display. |
| confirm          | boolean | false                        |          | Show a confirm dialog on click. |
| confirm_message  | string  | 'datatables.bulk.confirmMsg' |          | Display this confirm dialog message to the user. |
| attributes       | array   | array()                      |          | Add some element attributes e.g. to style link as a boostrap button (see example below). |
| render_if        | Closure | null                         |          | Render depending on a closure condition e.g. permissions. |

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

| Option          | Type           | Default                | Required | Description |
|-----------------|----------------|------------------------|----------|-------------|
| class           | string         | ''                     |          | Class to assign to each cell in the column. |
| default_content | null or string | null                   |          | Default content for a field that can have a null or undefined value. |
| padding         | string         | ''                     |          | Add padding to the text content used when calculating the optimal width for a table. |
| name            | string         | ''                     |          | Set a descriptive name for a column. |
| orderable       | boolean        | false                  |          | Enable or disable ordering on this column. |
| render          | null or string | null                   |          | This property will modify the data that is used by DataTables. |
| searchable      | boolean        | false                  |          | Enable or disable filtering on the data in this column. |
| title           | string         | ''                     |          | The displayed column title. This value is not translated automatically. |
| type            | string         | ''                     |          | Set the column type - used for filtering and sorting string processing. |
| visible         | boolean        | true                   |          | Display or hide this column completly so no data will be displayed in the browser. |
| width           | string         | ''                     |          | Adjust the column's width. This value will set the element attribute `style="width: XXXpx;`. |
| order_sequence  | null or array  | null                   |          | Control the default ordering direction. |
| filter          | array          | array('text', array()) |          | See [Filtering](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/filter.md#2-serverside-individual-column-filtering) for more information. |
| add_if          | Closure        | null                   |          | Add a closure to check e.g. the current user's permissions and display a column depending on that result. |
| default         | string         | ''                     |          | Default content for a field that can have an empty string. |

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

| Option          | Type           | Default                | Required | Description |
|-----------------|----------------|------------------------|----------|-------------|
| class           | string         | ''                     |          | Class to assign to each cell in the column. |
| default_content | null or string | null                   |          | Default content for a field that can have a null or undefined value. |
| padding         | string         | ''                     |          | Add padding to the text content used when calculating the optimal width for a table. |
| name            | string         | ''                     |          | Set a descriptive name for a column. |
| orderable       | boolean        | true                   |          | Enable or disable ordering on this column. |
| render          | null or string | 'render_boolean'       |          | This property will modify the data that is used by DataTables. |
| searchable      | boolean        | true                   |          | Enable or disable filtering on the data in this column. |
| title           | string         | ''                     |          | The displayed column title. This value is not translated automatically. |
| type            | string         | ''                     |          | Set the column type - used for filtering and sorting string processing. |
| visible         | boolean        | true                   |          | Display or hide this column completly so no data will be displayed in the browser. |
| width           | string         | ''                     |          | Adjust the column's width. This value will set the element attribute `style="width: XXXpx;`. |
| order_sequence  | null or array  | null                   |          | Control the default ordering direction. |
| true_icon       | string         | ''                     |          | Display a icon if the boolean condition is true e.g. `glyphicon glyphicon-ok`. |
| false_icon      | string         | ''                     |          | Display a icon if the boolean condition is false e.g. `glyphicon glyphicon-remove`. |
| true_label      | string         | ''                     |          | Display text if the boolean condition is true e.g. `yes`. |
| false_label     | string         | ''                     |          | Display text if the boolean condition is false e.g. `no`. |
| filter          | array          | see the below example  |          | See [Filtering](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/filter.md#2-serverside-individual-column-filtering) for more information. |
| add_if          | Closure        | null                   |          | Add a closure to check e.g. the current user's permissions and display a column depending on that result. |
| editable        | boolean        | false                  |          | Enable edit mode for this column. |
| editable_if     | Closure        | null                   |          | Enable edit mode for this column depending on a closure condition e.g. permissions. |

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

| Option          | Type           | Default                | Required | Description |
|-----------------|----------------|------------------------|----------|-------------|
| class           | string         | ''                     |          | Class to assign to each cell in the column. |
| default_content | null or string | null                   |          | Default content for a field that can have a null or undefined value. |
| padding         | string         | ''                     |          | Add padding to the text content used when calculating the optimal width for a table. |
| name            | string         | ''                     |          | Set a descriptive name for a column. |
| orderable       | boolean        | true                   |          | Enable or disable ordering on this column. |
| render          | null or string | 'render_datetime'      |          | This property will modify the data that is used by DataTables. |
| searchable      | boolean        | true                   |          | Enable or disable filtering on the data in this column. |
| title           | string         | ''                     |          | The displayed column title. This value is not translated automatically. |
| type            | string         | ''                     |          | Set the column type - used for filtering and sorting string processing. |
| visible         | boolean        | true                   |          | Display or hide this column completly so no data will be displayed in the browser. |
| width           | string         | ''                     |          | Adjust the column's width. This value will set the element attribute `style="width: XXXpx;`. |
| order_sequence  | null or array  | null                   |          | Control the default ordering direction. |
| filter          | array          | array('text', array()) |          | See [Filtering](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/filter.md#2-serverside-individual-column-filtering) for more information. |
| add_if          | Closure        | null                   |          | Add a closure to check e.g. the current user's permissions and display a column depending on that result. |
| date_format     | string         | 'lll'                  |          | Format date, see [Date Parameteres](http://php.net/manual/de/function.date.php#refsect1-function.date-parameters). |
| editable        | boolean        | false                  |          | Enable edit mode for this column. |
| editable_if     | Closure        | null                   |          | Enable edit mode for this column depending on a closure condition e.g. permissions. |

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

| Option          | Type           | Default                | Required | Description |
|-----------------|----------------|------------------------|----------|-------------|
| class           | string         | ''                     |          | Class to assign to each cell in the column. |
| default_content | null or string | null                   |          | Default content for a field that can have a null or undefined value. |
| padding         | string         | ''                     |          | Add padding to the text content used when calculating the optimal width for a table. |
| name            | string         | ''                     |          | Set a descriptive name for a column. |
| orderable       | boolean        | true                   |          | Enable or disable ordering on this column. |
| render          | null or string | 'render_timeago'       |          | This property will modify the data that is used by DataTables. |
| searchable      | boolean        | true                   |          | Enable or disable filtering on the data in this column. |
| title           | string         | ''                     |          | The displayed column title. This value is not translated automatically. |
| type            | string         | ''                     |          | Set the column type - used for filtering and sorting string processing. |
| visible         | boolean        | true                   |          | Display or hide this column completly so no data will be displayed in the browser. |
| width           | string         | ''                     |          | Adjust the column's width. This value will set the element attribute `style="width: XXXpx;`. |
| order_sequence  | null or array  | null                   |          | Control the default ordering direction. |
| filter          | array          | array('text', array()) |          | See [Filtering](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/filter.md#2-serverside-individual-column-filtering) for more information. |
| add_if          | Closure        | null                   |          | Add a closure to check e.g. the current user's permissions and display a column depending on that result. |

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

| Option     | Type         | Default   | Required | Description |
|------------|--------------|-----------|----------|-------------|
| class      | string       | ''        |          | Class to assign to each cell in the column. |
| padding    | string       | ''        |          | Add padding to the text content used when calculating the optimal width for a table. |
| name       | string       | ''        |          | Set a descriptive name for a column. |
| title      | string       | ''        |          | The displayed column title. This value is not translated automatically. |
| type       | string       | ''        |          | Set the column type - used for filtering and sorting string processing. |
| visible    | boolean      | true      |          | Display or hide this column completly so no data will be displayed in the browser. |
| width      | string       | ''        |          | Adjust the column's width. This value will set the element attribute `style="width: XXXpx;`. |
| start_html | string       | ''        |          | Start HTML - use in combination with `end_html` to define a wrapper element. |
| end_html   | string       | ''        |          | End HTML |
| add_if     | Closure      | null      |          | Add a closure to check e.g. the current user's permissions and display a column depending on that result. |
| actions    | array        |           | ✔        | The actions container, see [Action options](#action-options). |

### Action options

| Option           | Type    | Default                      | Required | Description |
|------------------|---------|------------------------------|----------|-------------|
| route            | string  |                              | ✔        | Redirect user to this route ... |
| route_parameters | array   | array()                      |          | ... using the following optional route parameters. |
| icon             | string  | ''                           |          | Display an icon e.g. `glyphicon glyphicon-new-window`. |
| label            | string  | ''                           |          | Optional label text to display. |
| confirm          | boolean | false                        |          | Show a confirm dialog on click. |
| confirm_message  | string  | 'datatables.bulk.confirmMsg' |          | Display this confirm dialog message to the user. |
| attributes       | array   | array()                      |          | Add some element attributes e.g. to style link as a boostrap button (see example below). |
| render_if        | Closure | null                         |          | Render depending on a closure condition e.g. permissions. |

### Example

``` php
$this->columnBuilder
    ->add(null, 'action', array(
        'title' => 'Actions',
        'start_html' => '<div class="wrapper_example_class">',
        'end_html' => '</div>',
        'add_if' => function() {
            return ($this->authorizationChecker->isGranted('ROLE_ADMIN'));
        },
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
                'render_if' => function($row) {
                    return (
                        $this->authorizationChecker->isGranted('ROLE_ADMIN') &&
                        $row['title'] === 'Title 1'
                    );
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
            )
        )
    ))
;
```
___

## 8. Multiselect column

### Default template

SgDatatablesBundle:Column:multiselect.html.twig

### Options

| Option             | Type        | Default | Required | Description |
|--------------------|-------------|---------|----------|-------------|
| class              | string      | ''      |          | Class to assign to each cell in the column. |
| padding            | string      | ''      |          | Add padding to the text content used when calculating the optimal width for a table. |
| name               | string      | ''      |          | Set a descriptive name for a column. |
| title              | string      | ''      |          | The displayed column title. This value is not translated automatically. |
| type               | string      | ''      |          | Set the column type - used for filtering and sorting string processing. |
| visible            | boolean     | true    |          | Display or hide this column completly so no data will be displayed in the browser. |
| width              | string      | ''      |          | Adjust the column's width. This value will set the element attribute `style="width: XXXpx;`. |
| start_html         | string      | ''      |          | Start HTML - use in combination with `end_html` to define a wrapper element. |
| end_html           | string      | ''      |          | End HTML |
| add_if             | Closure     | null    |          | Add a closure to check e.g. the current user's permissions and display a column depending on that result. |
| actions            | array       |         | ✔        | The actions container, see [Action options](#action-options). |
| attributes         | array       | array() |          | Add some element attributes e.g. to style link as a boostrap button (see example below). |
| value              | string      | 'id'    |          | |
| render_checkbox_if | Closure     | null    |          | Add a closure and render the current cell only if it does match some condition. |

### Multiselect-Action options

| Option           | Type    | Default                      | Required | Description |
|------------------|---------|------------------------------|----------|-------------|
| route            | string  |                              | ✔        | Redirect user to this route ... |
| route_parameters | array   | array()                      |          | ... using the following optional route parameters. |
| icon             | string  | ''                           |          | Display an icon e.g. `glyphicon glyphicon-new-window`. |
| label            | string  | ''                           |          | Optional label text to display. |
| confirm          | boolean | false                        |          | Show a confirm dialog on click. |
| confirm_message  | string  | 'datatables.bulk.confirmMsg' |          | Display this confirm dialog message to the user. |
| attributes       | array   | array()                      |          | Add some element attributes e.g. to style link as a boostrap button (see example below). |
| render_if        | Closure | null                         |          | Render depending on a closure condition e.g. permissions. |

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
        'add_if' => function() {
            return ($this->authorizationChecker->isGranted('ROLE_ADMIN'));
        },
        'render_checkbox_if' => function($row) {
            return ($row['public'] == true);
        },
        'actions' => array(
            array(
                'route' => 'post_bulk_delete',
                'label' => 'Delete',
                'render_if' => function() {
                    return ($this->authorizationChecker->isGranted('ROLE_ADMIN'));
                },
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

For proper display of images as thumbnails the LiipImagineBundle is required. Please follow all steps as described [here](http://symfony.com/doc/master/bundles/LiipImagineBundle/installation.html).

To upload images, I recommend the VichUploaderBundle. You can follow all steps as described [here](https://github.com/dustin10/VichUploaderBundle/blob/master/Resources/doc/index.md).

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

| Option                   | Type           | Default                | Required | Description |
|--------------------------|----------------|------------------------|----------|-------------|
| class                    | string         | ''                     |          | Class to assign to each cell in the column. |
| padding                  | string         | ''                     |          | Add padding to the text content used when calculating the optimal width for a table. |
| name                     | string         | ''                     |          | Set a descriptive name for a column. |
| orderable                | boolean        | false                  |          | Enable or disable ordering on this column. |
| searchable               | boolean        | false                  |          | Enable or disable filtering on the data in this column. |
| title                    | string         | ''                     |          | The displayed column title. This value is not translated automatically. |
| type                     | string         | ''                     |          | Set the column type - used for filtering and sorting string processing. |
| visible                  | boolean        | true                   |          | Display or hide this column completly so no data will be displayed in the browser. |
| width                    | string         | ''                     |          | Adjust the column's width. This value will set the element attribute `style="width: XXXpx;`. |
| order_sequence           | null or array  | null                   |          | Control the default ordering direction. |
| filter                   | array          | array('text', array()) |          | See [Filtering](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/filter.md#2-serverside-individual-column-filtering) for more information. |
| add_if                   | Closure        | null                   |          | Add a closure to check e.g. the current user's permissions and display a column depending on that result. |
| imagine_filter           | string         | ''                     |          | The imagine filter used to display image preview, see (LiipImagineBundle)[https://github.com/liip/LiipImagineBundle#basic-usage]. |
| imagine_filter_enlarged  | null or string | null                   |          | The imagine filter used to display the enlarged image's size. If not set or null, no filter will be applied. Option `enlarge` need to be set to true too. |
| relative_path            | string         |                        | ✔        | The relative path. |
| holder_url               | string         | ''                     |          | The placeholder url e.g. "http://placehold.it". |
| holder_width             | string         | '50'                   |          | The default width of the placeholder. |
| holder_height            | string         | '50'                   |          | The default height of the placeholder. |
| enlarge                  | boolean        | false                  |          | Enlarge thumbnail. |

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

For proper display of images as thumbnails the LiipImagineBundle is required. Please follow all steps as described [here](http://symfony.com/doc/master/bundles/LiipImagineBundle/installation.html).

To upload images, I recommend the VichUploaderBundle. You can follow all steps as described [here](https://github.com/dustin10/VichUploaderBundle/blob/master/Resources/doc/index.md).

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

| Option                  | Type           | Default                | Required | Description |
|-------------------------|----------------|------------------------|----------|-------------|
| class                   | string         | ''                     |          | Class to assign to each cell in the column. |
| padding                 | string         | ''                     |          | Add padding to the text content used when calculating the optimal width for a table. |
| name                    | string         | ''                     |          | Set a descriptive name for a column. |
| orderable               | boolean        | false                  |          | Enable or disable ordering on this column. |
| searchable              | boolean        | false                  |          | Enable or disable filtering on the data in this column. |
| title                   | string         | ''                     |          | The displayed column title. This value is not translated automatically. |
| type                    | string         | ''                     |          | Set the column type - used for filtering and sorting string processing. |
| visible                 | boolean        | true                   |          | Display or hide this column completly so no data will be displayed in the browser. |
| width                   | string         | ''                     |          | Adjust the column's width. This value will set the element attribute `style="width: XXXpx;`. |
| order_sequence          | null or array  | null                   |          | Control the default ordering direction. |
| filter                  | array          | array('text', array()) |          | See [Filtering](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/filter.md#2-serverside-individual-column-filtering) for more information. |
| add_if                  | Closure        | null                   |          | Add a closure to check e.g. the current user's permissions and display a column depending on that result. |
| imagine_filter          | string         |                        | ✔        | The imagine filter used to display image preview, see (LiipImagineBundle)[https://github.com/liip/LiipImagineBundle#basic-usage]. |
| imagine_filter_enlarged | null or string | null                   |          | The imagine filter used to display the enlarged image's size. If not set or null, no filter will be applied. Option `enlarge` need to be set to true too. |
| relative_path           | string         |                        | ✔        | The relative path. |
| holder_url              | string         | ''                     |          | The placeholder url e.g. "http://placehold.it". |
| holder_width            | string         | '50'                   |          | The default width of the placeholder. |
| holder_height           | string         | '50'                   |          | The default height of the placeholder. |
| enlarge                 | boolean        | false                  |          | Enlarge thumbnail. |
| view_limit              | integer        | 4                      |          | Maximum number of images to be displayed. |

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

This Column relies on [Bootstrap3](http://getbootstrap.com/).

### Default template

SgDatatablesBundle:Column:progress_bar.html.twig

### Options

| Option               | Type           | Default               | Required | Description |
|----------------------|----------------|-----------------------|----------|-------------|
| class                | string         | ''                    |          | Class to assign to each cell in the column. |
| padding              | string         | ''                    |          | Add padding to the text content used when calculating the optimal width for a table. |
| name                 | string         | ''                    |          | Set a descriptive name for a column. |
| orderable            | boolean        | true                  |          | Enable or disable ordering on this column. |
| render               | null or string | render_progress_bar   |          | This property will modify the data that is used by DataTables. |
| searchable           | boolean        | true                  |          | Enable or disable filtering on the data in this column. |
| title                | string         | ''                    |          | The displayed column title. This value is not translated automatically. |
| type                 | string         | ''                    |          | Set the column type - used for filtering and sorting string processing. |
| visible              | boolean        | true                  |          | Display or hide this column completly so no data will be displayed in the browser. |
| width                | string         | ''                    |          | Adjust the column's width. This value will set the element attribute `style="width: XXXpx;`. |
| order_sequence       | null or array  | null                  |          | Control the default ordering direction. |
| filter               | array          | see the below example |          | See [Filtering](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/filter.md#2-serverside-individual-column-filtering) for more information. |
| add_if               | Closure        | null                  |          | Add a closure to check e.g. the current user's permissions and display a column depending on that result. |
| bar_classes          | string         | ''                    |          | |
| value_min            | string         | '0'                   |          | |
| value_max            | string         | '100'                 |          | |
| label                | boolean        | true                  |          | |
| multi_color          | boolean        | false                 |          | |

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

- Define `getTemplate` to talk to datatables api : (ex. `SgDatatablesBundle:Column:column.html.twig`)
- Implement the `renderContent` function to render complexe contents
