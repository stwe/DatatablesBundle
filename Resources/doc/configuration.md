# Reference Configuration

```yaml
sg_datatables:
    datatable:
        templates:
            base: 'SgDatatablesBundle:Datatable:datatable.html.twig'
            html: 'SgDatatablesBundle:Datatable:datatable_html.html.twig'
            js:   'SgDatatablesBundle:Datatable:datatable_js.html.twig'
    site:
        title: 'SgDatatablesBundle'
        base_layout: 'SgDatatablesBundle:Crud:layout.html.twig'
        login_route: ~  # example: fos_user_security_login
        logout_route: ~ # example: fos_user_security_logout
    query:
        search_on_non_visible_columns: false
    global_prefix: admin
    # example
    #routes:
    #    post: post_datatable
    #fields:
    #    - { route: post, edit: [title, content, visible], new: [title, content, visible], show: [id, title, content, visible] }
```
