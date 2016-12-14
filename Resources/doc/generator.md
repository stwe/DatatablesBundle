# Use datatable class generator

``` bash
$ php app/console sg:datatable:generate AppBundle:Post
```

## Available Options

- `entity` (required): The entity name given as a shortcut notation containing the bundle name in which the entity is located and the name of the entity.

``` bash
$ php app/console sg:datatable:generate AppBundle:Post
```

- `--fields` (optional): The list of fields to generate in the class.

``` bash
$ php app/console sg:datatable:generate AppBundle:Post --fields="id name createdAt:timeago"
```

- `--bootstrap3` (optional): The Bootstrap3-Framework flag.

``` bash
$ php app/console sg:datatable:generate AppBundle:Post --bootstrap3
```

- `--ajax-url` (optional): The ajax url.

``` bash
$ php app/console sg:datatable:generate AppBundle:Post --ajax-url=test_path
```
