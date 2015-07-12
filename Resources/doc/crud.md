# Using the SgDatatablesBundle routing loader (unstable)

Don't forget to clear the cache!

## routing.yml

You need to add a few extra lines to the routing configuration

``` yaml
# routing.yml

SgDatatablesBundle:
    resource: .
    type: sg_datatables_routing
```

## config.yml

``` yaml
# config.yml

sg_datatables:
    routes:
        # The key is the route prefix, the value is the name of the datatable.
        post: post_datatable
    fields:
        - { route: post, edit: [title, content, visible], new: [title, content, visible], show: [id, title, content, visible] }
    roles:
        - { route: post, index: ROLE_ADMIN, edit: ROLE_ADMIN, new: ROLE_ADMIN, show: ROLE_USER }
```

## The generated routes

You don't need an own controller.

The bundle generates some routes for you. For the above example, they are:

| Name            | Method | Scheme | Host | Url               |
|-----------------|--------|--------|------|-------------------|
| sg_post_index   | GET    | ANY    | ANY  | /post/            |
| sg_post_results | GET    | ANY    | ANY  | /post/results     |
| sg_post_create  | POST   | ANY    | ANY  | /post/            |
| sg_post_new     | GET    | ANY    | ANY  | /post/new         |
| sg_post_show    | GET    | ANY    | ANY  | /post/{id}        |
| sg_post_edit    | GET    | ANY    | ANY  | /post/{id}/edit   |
| sg_post_update  | PUT    | ANY    | ANY  | /post/{id}        |
| sg_post_delete  | DELETE | ANY    | ANY  | /post/{id}        |
