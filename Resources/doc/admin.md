# Creating an Admin Section (unstable)

Don't forget to clear the cache!

## 1. routing.yml

```yaml
sg_datatables:
    resource: .
    type: sg_datatables_routing
```

## 2. Create your admin Datatable class

``` bash
$ php app/console sg:datatable:generate AppBundle:Post --admin
```

## 3. Registering your admin Datatable class as a Service

```yaml
app.datatable.post:
    class: AppBundle\Datatables\Admin\PostAdminDatatable
    tags:
        - { name: sg.datatable.view }
```

## 4. config.yml

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
                datatable: post_admin_datatable
                label_group: Post
                label: Postings
                #controller:
                #    index: AppBundle:PostAdmin:index
                #    edit: AppBundle:PostAdmin:edit
                #fields:
                #    show:
                #        - title
                #        - content
                #    new:
                #        - title
                #        - content
                #form_types:
                #    edit: AppBundle\Form\PostAdminType
            comment:
                class: AppBundle:Comment
                route_prefix: comment
                datatable: comment_admin_datatable
                label_group: Comment
                label: Comments
                #controller:
                #    index: AppBundle:CommentAdmin:index
                #    edit: AppBundle:CommentAdmin:edit
                #fields:
                #    show:
                #        - title
                #        - content
                #    new:
                #        - title
                #        - content
                #form_types:
                #    edit: AppBundle\Form\CommentAdminType
```

## 5. security.yml

```yaml
security:
    # ...

    access_control:
        - { path: ^/backend/, role: ROLE_ADMIN }
```

## 6. Go to the admin section

Browse the `/backend` URL in your application and you'll get access to the admin section.

## 7. The generated routes

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
