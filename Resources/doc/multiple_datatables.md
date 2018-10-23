# Multiple datatables on the same page

In your controller:

```php
public function index(Request $request) {
    $isAjax = $request->isXmlHttpRequest();
    $em     = $this->getDoctrine()->getManager();
    
    $datatable1 = $this->get('sg_datatables.factory')->create(Datatable1::class, "datatable1");
    $datatable1->buildDatatable();
    
    $datatable2 = $this->get('sg_datatables.factory')->create(Datatable2::class, "datatable2");
    $datatable2->buildDatatable();
    
    if ($isAjax) {
        $responseService = $this->get('sg_datatables.response');
        $responseService->setDatatables(array($datatable1, $datatable2));
        $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
    
        // If needed, you can customize the query according to the datatable:
        $qb = $datatableQueryBuilder->getQb();
    
        if ($responseService->getDatatableName() === "datatable1_datatable") {
            // $qb->andWhere(...);
        }
        elseif ($responseService->getDatatableName() === "datatable2_datatable") {
            // $qb->andWhere(...);
        }
    
        return $responseService->getResponse();
    }
    
    return $this->render('Bundle:view.html.twig', array(
        'datatable1' => $datatable1,
        'datatable2' => $datatable2
    ));
}
```

In your view:
```html
{% extends '::base.html.twig' %}

{% block main %}

    {{ sg_datatables_render(datatable1) }}
    
    {{ sg_datatables_render(datatable2) }}

{% endblock %}
```