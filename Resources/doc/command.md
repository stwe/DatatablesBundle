# Use the Datatable generator

``` bash
$ php bin/console sg:datatable:generate AppBundle:Post
```

## Available arguments

- `entity` (required): The entity class name as shortcut notation.

``` bash
$ php bin/console sg:datatable:generate AppBundle:Post
```

## Available options

- `--fields` or `-f` (optional): The fields to create columns in the Datatable.

``` bash
$ php bin/console sg:datatable:generate AppBundle:Post --fields="id name createdAt:datetime"
```

- `--overwrite` or `-o` (optional): Allow to overwrite an existing Datatable.

``` bash
$ php bin/console sg:datatable:generate AppBundle:Post -o
```
