# Reference Configuration

```yaml
sg_datatables:
    datatable:
        templates:
            base: 'SgDatatablesBundle:Datatable:datatable.html.twig'
            html: 'SgDatatablesBundle:Datatable:datatable_html.html.twig'
            js:   'SgDatatablesBundle:Datatable:datatable_js.html.twig'
        query:
            search_on_non_visible_columns: false
            translation_query_hints: false
    admin:
        site:
            title: 'SgDatatablesBundle'
            base_layout: 'SgDatatablesBundle:Crud:layout.html.twig'
            admin_route_prefix: 'admin'
            login_route: 'fos_user_security_login'
            logout_route: 'fos_user_security_logout'
        entities:
            post:
                class: AppBundle:Post
                route_prefix: post
                datatable: post_admin_datatable
                label_group: Post
                label: Postings
                #heading:
                #    index: 'Index'
                #    show: 'Show'
                #    edit: 'Edit'
                #    new: 'New'
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
```
