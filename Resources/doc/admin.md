# Creating an Admin Section (unstable)

Don't forget to clear the cache!

## routing.yml

```yaml
sg_datatables:
    resource: .
    type: sg_datatables_routing
```

## config.yml

```yaml
sg_datatables:
    # ...
    admin:
        site:
            title: 'SgDatatablesBundle'
            base_layout: 'SgDatatablesBundle:Crud:layout.html.twig'
            admin_route_prefix: 'backend'
            login_route: 'fos_user_security_login'
            logout_route: 'fos_user_security_logout'
        entities:
            post:
                class: AppBundle:Post
                route_prefix: post
                datatable: post_datatable
                label: Postings
                #controller:
                #    index: AppBundle:Post:index
                #    edit: AppBundle:Post:edit
                #fields:
                #    show:
                #        - title
                #        - content
                #    new:
                #        - title
                #        - content
                #form_types:
                #    edit: AppBundle\Form\PostType
            comment:
                class: AppBundle:Comment
                route_prefix: comment
                datatable: comment_datatable
                label: Comments
                #controller:
                #    index: AppBundle:Comment:index
                #    edit: AppBundle:Comment:edit
                #fields:
                #    show:
                #        - title
                #        - content
                #    new:
                #        - title
                #        - content
                #form_types:
                #    edit: AppBundle\Form\CommentType
```

## security.yml

```yaml
security:
    # ...

    access_control:
        - { path: ^/backend/, role: ROLE_ADMIN }
```

## The generated routes

This bundle generates some routes for your admin section. For the above example, they are:

| Name                     | Method | Scheme | Host | Path                       |
|--------------------------|--------|--------|------|----------------------------|
| sg_admin_home            | GET    |  ANY   | ANY  | /backend/                  |
| sg_admin_post_index      | GET    |  ANY   | ANY  | /backend/post/             |
| sg_admin_post_results    | GET    |  ANY   | ANY  | /backend/post/results      |
| sg_admin_post_create     | POST   |  ANY   | ANY  | /backend/post/             |
| sg_admin_post_new        | GET    |  ANY   | ANY  | /backend/post/new          |
| sg_admin_post_show       | GET    |  ANY   | ANY  | /backend/post/{id}         |
| sg_admin_post_edit       | GET    |  ANY   | ANY  | /backend/post/{id}/edit    |
| sg_admin_post_update     | PUT    |  ANY   | ANY  | /backend/post/{id}         |
| sg_admin_post_delete     | DELETE |  ANY   | ANY  | /backend/post/{id}         |
| sg_admin_comment_index   | GET    |  ANY   | ANY  | /backend/comment/          |
| sg_admin_comment_results | GET    |  ANY   | ANY  | /backend/comment/results   |
| sg_admin_comment_create  | POST   |  ANY   | ANY  | /backend/comment/          |
| sg_admin_comment_new     | GET    |  ANY   | ANY  | /backend/comment/new       |
| sg_admin_comment_show    | GET    |  ANY   | ANY  | /backend/comment/{id}      |
| sg_admin_comment_edit    | GET    |  ANY   | ANY  | /backend/comment/{id}/edit |
| sg_admin_comment_update  | PUT    |  ANY   | ANY  | /backend/comment/{id}      |
| sg_admin_comment_delete  | DELETE |  ANY   | ANY  | /backend/comment/{id}      |
