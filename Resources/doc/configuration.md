# Reference Configuration

```yaml
sg_datatables:
    default_layout:
        page_length:          10
        server_side:          true
        processing:           true
        individual_filtering: false
        templates:
            base: 'SgDatatablesBundle:Datatable:datatable.html.twig'
            html: 'SgDatatablesBundle:Datatable:datatable_html.html.twig'
            js:   'SgDatatablesBundle:Datatable:datatable_js.html.twig'
```
