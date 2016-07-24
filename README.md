# SgDatatablesBundle

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/61803d08-17ab-4a69-ad13-6ec448762332/big.png)](https://insight.sensiolabs.com/projects/61803d08-17ab-4a69-ad13-6ec448762332)

[![knpbundles.com](http://knpbundles.com/stwe/DatatablesBundle/badge)](http://knpbundles.com/stwe/DatatablesBundle)

[![Build Status](https://travis-ci.org/stwe/DatatablesBundle.svg?branch=master)](https://travis-ci.org/stwe/DatatablesBundle)

[![Latest Stable Version](https://poser.pugx.org/sg/datatablesbundle/v/stable)](https://packagist.org/packages/sg/datatablesbundle) [![Total Downloads](https://poser.pugx.org/sg/datatablesbundle/downloads)](https://packagist.org/packages/sg/datatablesbundle) [![Latest Unstable Version](https://poser.pugx.org/sg/datatablesbundle/v/unstable)](https://packagist.org/packages/sg/datatablesbundle) [![License](https://poser.pugx.org/sg/datatablesbundle/license)](https://packagist.org/packages/sg/datatablesbundle)

## Recent Changes

### Select2 support / Multiple select filter (#287)

#### Remote example from the Docs

```php
$this->columnBuilder
    ->add('fruitcolor.color', 'column', array(
        'title' => 'Fruchtfarbe',
        'filter' => array('select2', array(
            //'select_options' => array('' => 'Alle') + $this->getCollectionAsOptionsArray($fruitcolor, 'color', 'color'),
            'search_type' => 'eq',
            'multiple' => true,
            'placeholder' => null,
            'allow_clear' => true,
            'tags' => true,
            'language' => 'de',
            'url' => 'select2_color',
            'delay' => 250,
            'cache' => true
        )),
    ))
;
```

```php
/**
 * @param Request $request
 *
 * @Route("/ajax/select2-color", name="select2_color")
 *
 * @return JsonResponse|Response
 */
public function select2Color(Request $request)
{
    if ($request->isXmlHttpRequest()) {
        $em = $this->getDoctrine()->getManager();
        $colors = $em->getRepository('AppBundle:Fruitcolor')->findAll();

        $result = array();

        /** @var \AppBundle\Entity\Fruitcolor $color */
        foreach ($colors as $color) {
            $result[$color->getId()] = $color->getColor();
        }

        return new JsonResponse($result);
    }

    return new Response('Bad request.', 400);
}
```

### In-place editing callback (#372)

```
$this->columnBuilder
    ->add('name', 'column', array(
        'title' => 'Name',
        'editable' => true,
        'editable_if' => function($row) {
            return (
                $this->authorizationChecker->isGranted('ROLE_USER') &&
                $row['public'] == true
            );
        }
    ))
```

### Pipelining to reduce Ajax calls

```
$this->ajax->set(array(
    'url' => $this->router->generate('chili_private_results'),
    'pipeline' => 6
));
```

### Search result highlighting.

1. Include the [jQuery Highlight Plugin](http://bartaz.github.io/sandbox.js/jquery.highlight.html)
2. Configure your Datatables-Class features

```
$this->features->set(array(
    // ...
    'highlight' => true,
    'highlight_color' => 'red' // 'red' is the default value
));
```

### Enlargement of thumbnails with Featherlight

see [#401](https://github.com/stwe/DatatablesBundle/issues/401)

The Bootstrap modal window does not work properly in responsive mode.
 
Load [Featherlight](https://github.com/noelboss/featherlight/) with your base layout.  

### `add_if` Closure for all Columns and TopActions

```
$this->columnBuilder
    ->add('title', 'column', array(
        // ...
        'add_if' => function() {
            return ($this->authorizationChecker->isGranted('ROLE_ADMIN'));
        },
    ))
;
```

```
$this->topActions->set(array(
    // ...
    'add_if' => function() {
        return ($this->authorizationChecker->isGranted('ROLE_ADMIN'));
    },
    'actions' => array(
        // ...
    )
));
```

### Render Actions

**before**

```
'actions' => array(
    array(
        'route' => 'post_edit',
        'route_parameters' => array(
            'id' => 'id'
        ),
        'role' => 'ROLE_ADMIN',
        'render_if' => function($row) {
            return ($row['title'] === 'Title 1');
        },
    ),
    // ...
```

**after**

```
'actions' => array(
    array(
        'route' => 'post_edit',
        'route_parameters' => array(
            'id' => 'id'
        ),
        'render_if' => function($row) {
            return (
                $this->authorizationChecker->isGranted('ROLE_USER') &&
                $row['user']['username'] == $this->getUser()->getUsername()
            );
        },
    ),
    // ...
```

### Multiselect: render checkboxes only if conditions are True

```
$this->columnBuilder
    ->add('title', 'multiselect', array(
        // ...
        'render_checkbox_if' => function($row) {
            return ($row['public'] == true);
        },
    ))
;
```

## Screenshots

### Table with Bootstrap3 integration: 

<div style="text-align:center"><img alt="Screenshot" src="https://github.com/stwe/DatatablesBundle/raw/master/Resources/images/sc1.jpg"></div>

## Documentation

[Installation](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/installation.md)

[Column types](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/columns.md)

[In-place editing](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/editable.md)

[How to use the ColumnBuilder](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/columnBuilder.md)

[Setup Datatable Class](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/setup.md)

[Filtering](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/filter.md)

[To use a line formatter](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/lineFormatter.md)

[Query callbacks](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/query.md)

[Extensions like Buttons or Responsive](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/extensions.md)

[Options of the generator](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/generator.md)

[Reference configuration](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/configuration.md)

## Example

[Demo Application](https://github.com/stwe/DtBundleDemo)

## Integrating 3rd party stuff 

[Integrate Bootstrap3](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/bootstrap3.md)

[Integrate the Translatable behavior extension for Doctrine 2](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/translatable.md)

[Integrate the LiipImagineBundle / ImageColumn, GalleryColumn and thumbnails](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/thumbs.md)

## Limitations and Known Issues

Much like every other piece of software `SgDatatablesBundle` is not perfect and far from feature complete.

- This bundle does not support multiple Ids.
- Searching and filtering on a virtual column not yet implemented and disabled by default.
- PostgreSql is currently not fully supported.

## Reporting an issue or a feature request

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/stwe/DatatablesBundle/issues).

**You must know that all the pull requests you are going to submit must be released under the MIT license.**

## Friendly License

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE

You are free to use, modify and distribute this software, as long as the copyright header is left intact (specifically the comment block which starts with /*)!
