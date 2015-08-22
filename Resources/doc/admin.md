# Creating an Admin Section (new, unstable and experimental)

Don't forget to clear the cache!

## routing.yml

```yaml
# routing.yml

sg_datatables:
    resource: .
    type: sg_datatables_routing
```

## config.yml

```yaml
# config.yml

sg_datatables:
    global_prefix: backend
    site:
        title: My admin section
        login_route: fos_user_security_login
        logout_route: fos_user_security_logout
    routes:
        # The key is the route prefix, the value is the name of the datatable.
        post: post_datatable
    fields:
        post: { edit: [title, content, visible], new: [title, content, visible], show: [id, title, content, visible] }
    controller:
        post: { index: AppBundle:Admin:index }
```

## security.yml

```yaml
# security.yml

security:
    # ...

    access_control:
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/backend/, role: ROLE_ADMIN }
```

## The generated routes

This bundle generates some routes for your admin section. For the above example, they are:

| Name            | Method | Scheme | Host | Path                      |
|-----------------|--------|--------|------|---------------------------|
| sg_post_index   | GET    | ANY    | ANY  | /backend/post/            |
| sg_post_results | GET    | ANY    | ANY  | /backend/post/results     |
| sg_post_create  | POST   | ANY    | ANY  | /backend/post/            |
| sg_post_new     | GET    | ANY    | ANY  | /backend/post/new         |
| sg_post_show    | GET    | ANY    | ANY  | /backend/post/{id}        |
| sg_post_edit    | GET    | ANY    | ANY  | /backend/post/{id}/edit   |
| sg_post_update  | PUT    | ANY    | ANY  | /backend/post/{id}        |
| sg_post_delete  | DELETE | ANY    | ANY  | /backend/post/{id}        |
