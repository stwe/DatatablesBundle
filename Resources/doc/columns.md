# Columns

1. [Column](#1-column)
2. [Boolean Column](#2-boolean-column)
3. [DateTime Column](#3-datetime-column)
4. [Image Column](#4-image-column)
5. [Virtual Column](#5-virtual-column)
6. [Action Column](#6-action-column)
7. [Multiselect Column](#7-multiselect-column)
8. [Number Column](#8-number-column)

## 1. Column

Represents the most basic column.

### Options template

SgDatatablesBundle:column:column.html.twig

### Cell content template

SgDatatablesBundle:render:column.html.twig

### Options

With 'null' initialized options uses the default value of the DataTables plugin.

| Option              | Type               | Default           | Required | Description |
|---------------------|--------------------|-------------------|----------|-------------|
| cell_type           | null or string     | null              |          | Cell type to be created for a column. |
| class_name          | null or string     | null              |          | Class to assign to each cell in the column. |
| content_padding     | null or string     | null              |          | Add padding to the text content used when calculating the optimal with for a table. |
| dql                 | null or string     |                   |          | Custom DQL code for the column. |
| data                | null or string     |                   |          | The data source for the column. |
| default_content     | null or string     | null              |          | Set default, static, content for a column. |
| name                | null or string     | null              |          | Set a descriptive name for a column. |
| orderable           | bool               | true              |          | Enable or disable ordering on this column. |
| order_data          | null, array or int | null              |          | Define multiple column ordering as the default order for a column. |
| order_sequence      | null or array      | null              |          | Order direction application sequence. |
| searchable          | bool               | true              |          | Enable or disable filtering on the data in this column. |
| title               | null or string     | null              |          | Set the column title. |
| visible             | bool               | true              |          | Enable or disable the display of this column. |
| width               | null or string     | null              |          | Column width assignment. |
| add_if              | null or Closure    | null              |          | Add column only if conditions are TRUE. |
| join_type           | string             | 'leftJoin'        |          | Join type (default: 'leftJoin'), if the column represents an association. |
| type_of_field       | null or string     | null (autodetect) |          | Set the data type itself for ordering (example: integer instead string). |
| responsive_priority | null or int        | null              |          | Set column's visibility priority. Requires the Responsive extension. |
| filter              | array              | TextFilter        |          | A Filter instance for individual filtering. |
| editable            | array or null      | null              |          | An Editable instance for in-place editing. |

### Example

``` php
$this->columnBuilder
    ->add('title', Column::class, array(
        'title' => 'Title',
        'searchable' => true,
        'orderable' => true,
        'editable' => array(TextEditable::class, array(
            //'pk' => 'cid',
            'placeholder' => 'Edit value',
            'empty_text' => 'Empty Text'
        )),
        'filter' => array(SelectFilter::class, array(
            'multiple' => true,
            'cancel_button' => true,
            'select_search_types' => array(
                '' => null,
                '2' => 'like',
                '1' => 'eq',
                'send_isNull' => 'isNull',
                'send_isNotNull' => 'isNotNull'
            ),
            'select_options' => array(
                '' => 'Any',
                '2' => 'Title with the digit 2',
                '1' => 'Title with the digit 1',
                'send_isNull' => 'is Null',
                'send_isNotNull' => 'is not Null'
            ),
        )),
        'type_of_field' => 'integer', // If the title consists only of digits.
        /*
        'add_if' => function() {
            return $this->authorizationChecker->isGranted('ROLE_USER');
        },
        */
    ))
;
```

**For many-to-one associations:**

``` php
$users = $this->em->getRepository('AppBundle:User')->findAll();

$this->columnBuilder
    ->add('createdBy.username', Column::class, array(
        'title' => 'Created by',
        'searchable' => true,
        'orderable' => true,
        'filter' => array(SelectFilter::class, array(
            'select_options' => array('' => 'All') + $this->getOptionsArrayFromEntities($users, 'username', 'username'),
            'search_type' => 'eq'
        ))
    ))
;
```

**For one-to-many or many-to-many associations:**

``` php
// a post has many comments
$this->columnBuilder
    ->add('comments.title', Column::class, array(
        'title' => 'Comments',
        'data' => 'comments[,].title',
        'searchable' => true,
        'orderable' => true,
    ))
;
```

``` php
// a post has many comments and each comment has an user with an username
$this->columnBuilder
    ->add('comments.createdBy.username', Column::class, array(
        'title' => 'comments usernames',
        'data' => 'comments[,].createdBy.username'
    ))
;
```

**With custom DQL:**

``` php
$this->columnBuilder
    ->add('full_name', Column::class, array(
        'title' => 'Full name',
        'dql' => "CONCAT(user.firstname, ' ', user.lastname)",
        'searchable' => true,
        'orderable' => true,
    ))
    // If you want to use a subquery, please put subquery between parentheses and subquery aliases between braces.
    // Note that subqueries cannot be search with a "LIKE" clause.
    ->add('post_count', Column::class, array(
        'title' => 'User posts count',
        'dql' => '(SELECT COUNT({p}) FROM MyBundle:Post {p} WHERE {p}.user = user)',
        'searchable' => true,
        'orderable' => true,
    ))
;
```
___

## 2. Boolean column

Represents a column, optimized for boolean values.

### Options template

SgDatatablesBundle:column:column.html.twig

### Cell content template

SgDatatablesBundle:render:boolean.html.twig

### Options

All options of [Column](#1-column).

The option `filter` is set to `SelectFilter` by default.

**Additional:**

| Option      | Type               | Default | Required | Description     |
|-------------|--------------------|---------|----------|-----------------|
| true_icon   | null or string     | null    |          | Icon for true   |
| false_icon  | null or string     | null    |          | Icon for false  |
| true_label  | null or string     | null    |          | Label for true  |
| false_label | null or string     | null    |          | Label for false |

### Example

``` php
$this->columnBuilder
    ->add('visible', BooleanColumn::class, array(
        'title' => 'Visible',
        'searchable' => true,
        'orderable' => true,
        'true_label' => 'Yes',
        'false_label' => 'No',
        'default_content' => 'Default Value',
        'true_icon' => 'glyphicon glyphicon-ok',
        'false_icon' => 'glyphicon glyphicon-remove',
        'filter' => array(SelectFilter::class, array(
            'classes' => 'test1 test2',
            'search_type' => 'eq',
            'multiple' => true,
            'select_options' => array(
                '' => 'Any',
                '1' => 'Yes',
                '0' => 'No'
            ),
            'cancel_button' => true,
        )),
        'editable' => array(SelectEditable::class, array(
            'source' => array(
                array('value' => 1, 'text' => 'Yes'),
                array('value' => 0, 'text' => 'No'),
                array('value' => null, 'text' => 'Null')
            ),
            'mode' => 'inline',
            //'pk' => 'cid',
            'empty_text' => '',
        )),
    ))
;
```
___

## 3. DateTime column

Represents a column, optimized for date time values.

**Be sure to install the [Moment.js](https://momentjs.com/) plugin before using this column.**
**Be sure to install the [Bootstrap Date Range Picker](http://www.daterangepicker.com/) plugin before using the DateRangeFilter.**

### Options template

SgDatatablesBundle:column:column.html.twig

### Cell content template

SgDatatablesBundle:render:datetime.html.twig

### Options

All options of [Column](#1-column).

**Additional:**

| Option      | Type   | Default | Required | Description              |
|-------------|--------|---------|----------|--------------------------|
| date_format | string | lll     |          | Moment.js date format.   |
| timeago     | bool   | false   |          | Use the time ago format. |

### Example

``` php
$this->columnBuilder
    ->add('publishedAt', DateTimeColumn::class, array(
        'title' => 'Published at',
        'default_content' => 'No value',
        'date_format' => 'L',
        'filter' => array(DateRangeFilter::class, array(
            'cancel_button' => true,
        )),
        'editable' => array(CombodateEditable::class, array(
            'format' => 'YYYY-MM-DD',
            'view_format' => 'DD.MM.YYYY',
            //'pk' => 'cid'
        )),
        'timeago' => true
    ))
;
```
___

## 4. Image column

Represents a column, optimized for images.

**The LiipImagineBundle is required. Please follow all steps as described [here](http://symfony.com/doc/master/bundles/LiipImagineBundle/installation.html).**

**To upload images, I recommend the VichUploaderBundle. You can follow all steps as described [here](https://github.com/dustin10/VichUploaderBundle/blob/master/Resources/doc/index.md).**

**Be sure to install the [Featherlight.js](http://noelboss.github.io/featherlight/) plugin before using this column.**

A complete example of an ImageColumn can be found in the [demo bundle](https://github.com/stwe/DtBundleDemo10).

### Options template

SgDatatablesBundle:column:column.html.twig

### Cell content template

SgDatatablesBundle:render:thumb.html.twig

### Options

All options of [Column](#1-column), except `editable`.

**Additional:**

| Option                   | Type           | Default | Required | Description              |
|--------------------------|----------------|---------|----------|--------------------------|
| imagine_filter           | string         |         | x        | The imagine filter used to display image preview. |
| relative_path            | string         |         | x        | The relative path. |
| imagine_filter_enlarged  | string or null | null    |          | The imagine filter used to display the enlarged image's size. |
| holder_url               | string or null | null    |          | The placeholder url (e.g. "http://placehold.it"). |
| holder_width             | string         | '50'    |          | The default width of the placeholder. |
| holder_height            | string         | '50'    |          | The default height of the placeholder. |
| enlarge                  | bool           | false   |          | Enlarge thumbnail. |

### Example

#### Image

``` php
$this->columnBuilder
    ->add('image', ImageColumn::class, array(
        'title' => 'Image',
        'imagine_filter' => 'thumbnail_50_x_50',
        'imagine_filter_enlarged' => 'thumbnail_250_x_250',
        'relative_path' => 'images',
        'holder_url' => 'https://placehold.it',
        'enlarge' => true,
    ))
;
```

#### Gallery (one-to-many association)

``` php
$this->columnBuilder
    ->add('images.fileName', ImageColumn::class, array(
        'title' => 'Images',
        'data' => 'images[ ].fileName',
        'imagine_filter' => 'thumbnail_50_x_50',
        'imagine_filter_enlarged' => 'thumbnail_250_x_250',
        'relative_path' => 'images',
        'holder_url' => 'https://placehold.it',
        'enlarge' => true,
    ))
;
```
___

## 5. Virtual column

Represents a virtual column.

### Options template

SgDatatablesBundle:Column:column.html.twig

### Options

All options of [Column](#1-column), except `data`, `join_type` and `editable`.

The options `searchable` and `orderable` are set to `false` by default.

**Additional:**

| Option        | Type               | Default | Required | Description     |
|---------------|--------------------|---------|----------|-----------------|
| order_column  | null or string     | null    |          | The name of an existing column that is used for ordering. |
| search_column | null or string     | null    |          | The name of an existing column that is used for searching. |

### Example

``` php
public function getLineFormatter()
{
    $formatter = function($row) {
        $row['test'] = 'Post from ' . $row['createdBy']['username'];

        return $row;
    };

    return $formatter;
}

public function buildDatatable(array $options = array())
{
    // ...

    $users = $this->em->getRepository('AppBundle:User')->findAll();

    $this->columnBuilder
        ->add('test', VirtualColumn::class, array(
            'title' => 'Test virtual',
            'searchable' => true,
            'orderable' => true,
            'order_column' => 'createdBy.username', // use the 'createdBy.username' column for ordering
            'search_column' => 'createdBy.username', // use the 'createdBy.username' column for searching
        ))
        ->add('createdBy.username', Column::class, array(
            'title' => 'Created by',
            'searchable' => true,
            'orderable' => true,
            'filter' => array(SelectFilter::class, array(
                'select_options' => array('' => 'All') + $this->getOptionsArrayFromEntities($users, 'username', 'username'),
                'search_type' => 'eq'
            ))
        ))

        // ...
    ;
}
```
___

## 6. Action column

A Column to display CRUD action labels or buttons.

### Options template

SgDatatablesBundle:Column:column.html.twig

### Cell content template

SgDatatablesBundle:render:action.html.twig

### Action column options

| Option          | Type               | Default           | Required | Description |
|-----------------|--------------------|-------------------|----------|-------------|
| cell_type       | null or string     | null              |          | Cell type to be created for a column. |
| class_name      | null or string     | null              |          | Class to assign to each cell in the column. |
| content_padding | null or string     | null              |          | Add padding to the text content used when calculating the optimal with for a table. |
| name            | null or string     | null              |          | Set a descriptive name for a column. |
| title           | null or string     | null              |          | Set the column title. |
| visible         | bool               | true              |          | Enable or disable the display of this column. |
| width           | null or string     | null              |          | Column width assignment. |
| add_if          | null or Closure    | null              |          | Add column only if conditions are TRUE. |
| actions         | array              |                   | X        | Contains all the actions. |
| start_html      | null or string     | null              |          | HTML code before all actions. |
| end_html        | null or string     | null              |          | HTML code after all actions. |

### Action options

| Option              | Type                   | Default | Required | Description |
|---------------------|------------------------|---------|----------|-------------|
| route               | null or string         | null    |          | The name of the Action route. |
| route_parameters    | null, array or Closure | null    |          | The route parameters. |
| icon                | null or string         | null    |          | An icon for the Action. |
| label               | null or string         | null    |          | A label for the Action. |
| confirm             | bool                   | false   |          | Show confirm message if true. |
| confirm_message     | null or string         | null    |          | The confirm message. |
| attributes          | null or array          | null    |          | HTML Tag attributes (except 'href' and 'value'). |
| button              | bool                   | false   |          | Render a button instead of a link. |
| button_value        | null or string         | null    |          | The button value. |
| button_value_prefix | bool                   | false   |          | Use the Datatable-Name as prefix for the button value. |
| render_if           | null or Closure        | null    |          | Render an Action only if conditions are TRUE. |
| start_html          | null or string         | null    |          | HTML code before the <a> Tag. |
| end_html            | null or string         | null    |          | HTML code after the <a> Tag. |

### Example

**Don't forget to add the following to your route annotations:**

``` php
options = {"expose" = true}
```

#### The Controller

``` php
/**
 * Finds and displays a Post entity.
 *
 * @param Post $post
 *
 * @Route("/{_locale}/{id}.{_format}", name = "post_show", options = {"expose" = true})
 * @Method("GET")
 * @Security("has_role('ROLE_USER')")
 *
 * @return Response
 */
public function showAction(Post $post)
{
    $deleteForm = $this->createDeleteForm($post);

    return $this->render('post/show.html.twig', array(
        'post' => $post,
        'delete_form' => $deleteForm->createView(),
    ));
}
```

#### The Datatable Class

``` php
$this->columnBuilder
    ->add(null, ActionColumn::class, array(
        'title' => 'Actions',
        'start_html' => '<div class="start_actions">',
        'end_html' => '</div>',
        'actions' => array(
            array(
                'route' => 'post_show',
                'route_parameters' => array(
                    'id' => 'id',
                    'type' => 'testtype',
                    '_format' => 'html',
                    '_locale' => 'en',
                ),
                'icon' => 'glyphicon glyphicon-eye-open',
                'label' => 'Show Posting < a >',
                'confirm' => true,
                'confirm_message' => 'Are you sure?',
                'attributes' => array(
                    'rel' => 'tooltip',
                    'title' => 'Show',
                    'class' => 'btn btn-primary btn-xs',
                    'role' => 'button',
                ),
                'render_if' => function ($row) {
                    return $row['createdBy']['username'] === 'user' && $this->authorizationChecker->isGranted('ROLE_USER');
                },
                'start_html' => '<div class="start_show_action">',
                'end_html' => '</div>',
            ),
            array(
                'icon' => 'glyphicon glyphicon-star',
                'label' => 'A < button >',
                'confirm' => false,
                'attributes' => array(
                    'rel' => 'tooltip',
                    'title' => 'Show',
                    'class' => 'btn btn-primary btn-xs',
                    'role' => 'button',
                ),
                'button' => true,
                'button_value' => 'id',
                'button_value_prefix' => true,
                'render_if' => function ($row) {
                    return $this->authorizationChecker->isGranted('ROLE_ADMIN');
                },
                'start_html' => '<div class="start_show_action">',
                'end_html' => '</div>',
            ),
        ),
    ))
;
```
___

## 7. Multiselect column

Support for Bulk Actions.

### Options template

SgDatatablesBundle:Column:multiselect.html.twig

### Cell content template

SgDatatablesBundle:render:multiselect.html.twig

### Options

All options of [Action Column](#4-action-column), except `title`.

**Additional Column options:**

| Option               | Type               | Default | Required | Description     |
|----------------------|--------------------|---------|----------|-----------------|
| attributes           | null or array      | null    |          | HTML <input> Tag attributes (except 'type' and 'value'). |
| value                | string             | 'id'    |          | A checkbox value, generated by column name. |
| value_prefix         | bool               | false   |          | Use the Datatable-Name as prefix for the value. |
| render_actions_to_id | null or string     | null    |          | Id selector where all multiselect actions are rendered. |
| render_if            | null or Closure    | null    |          | Render a Checkbox only if conditions are TRUE. |

### Example

#### The Controller

``` php
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
            $entity = $repository->find($choice['id']);
            $em->remove($entity);
        }

        $em->flush();

        return new Response('Success', 200);
    }

    return new Response('Bad Request', 400);
}
```

#### The Datatable Class

**If the MultiselectColumn is the first Column (position: 0), it is not orderable. Change the initial order statement:**

``` php
$this->options->set(array(
    'order' => array(array(1, 'asc')),
));

$this->columnBuilder
    ->add(null, MultiselectColumn::class, array(
        'start_html' => '<div class="start_checkboxes">',
        'end_html' => '</div>',
        'add_if' => function() {
            // add Column if condition is true
            return $this->authorizationChecker->isGranted('ROLE_USER');
        },
        //'attributes' => array('data-toggle' => 'toggle'),
        'value' => 'id',
        'value_prefix' => true,
        'render_actions_to_id' => 'sidebar-multiselect-actions', // custom Dom id for the actions
        'render_if' => function($row) {
            // render checkbox only if the Post created by 'user'
            return $row['createdBy']['username'] === 'user';
        },
        'actions' => array(
            array(
                'route' => 'post_bulk_delete',
                'icon' => 'glyphicon glyphicon-ok',
                'label' => 'Delete Postings',
                'attributes' => array(
                    'rel' => 'tooltip',
                    'title' => 'Delete',
                    'class' => 'btn btn-primary btn-xs',
                    'role' => 'button'
                ),
                'confirm' => true,
                'confirm_message' => 'Really?',
                'start_html' => '<div class="start_delete_action">',
                'end_html' => '</div>',
                'render_if' => function(/* the $row argument is not available in this context */) {
                    return $this->authorizationChecker->isGranted('ROLE_USER');
                }
            ),
        )
    ))
```
___

## 8. Number column

Represents a column, optimized for numbers.

**The intl extension is needed.**

### Options template

SgDatatablesBundle:Column:column.html.twig

### Options

All options of [Column](#1-column).

**Additional:**

| Option              | Type                   | Default                               | Required | Description     |
|---------------------|------------------------|---------------------------------------|----------|-----------------|
| formatter           | NumberFormatter Object |                                       | X        | A NumberFormatter instance. |
| use_format_currency | bool                   | false                                 |          | Use NumberFormatter::formatCurrency instead NumberFormatter::format to format the value. |
| currency            | null or string         | NumberFormatter::INTL_CURRENCY_SYMBOL |          | The currency code (e.g. EUR). |

### Example

``` php
public function buildDatatable(array $options = array())
{
    // ...

    $formatter = new \NumberFormatter("de_DE", \NumberFormatter::CURRENCY);
    
    // other example:
    // $numberFormatter = new \NumberFormatter('en_US', \NumberFormatter::DECIMAL);
    // $numberFormatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 2);

    $this->columnBuilder
        ->add('currency', NumberColumn::class, array(
            'formatter' => $formatter,
            'use_format_currency' => true, // needed for \NumberFormatter::CURRENCY
            'currency' => 'EUR',
            'searchable' => true,
            'orderable' => true,
            'editable' => array(TextEditable::class, array(
                'pk' => 'id',
                'mode' => 'inline',
            )),
        ))

        // ...
    ;
}
```
___
