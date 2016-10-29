# Getting Started With SgDatatablesBundle

This Bundle integrates the jQuery DataTables 1.10.x plugin into your Symfony application.

## Your First Datatable

### Step 1: 

### Step 2: Registering your Datatable as a Service

### Step 3: Add controller action

```php
/**
 * Lists all Post entities.
 *
 * @param Request $request
 *
 * @Route("/", name="post_index")
 * @Method("GET")
 *
 * @return Response
 */
public function indexAction(Request $request)
{
    $isAjax = $request->isXmlHttpRequest();

    $datatable = $this->get('app.datatable.post');
    $datatable->buildDatatable();

    if ($isAjax) {
        $responseService = $this->get('sg_datatables.response');
        // change now to POST request type if needed; default: 'GET'
        //$responseService->setType('POST');
        $responseService->setDatatable($datatable);

        $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
        $datatableQueryBuilder->buildQuery();

        return $responseService->getResponse();
    }

    return $this->render('post/index.html.twig', array(
        'datatable' => $datatable,
    ));
}
```

### Step 4: Create your index.html.twig

```html
{% extends '::base.html.twig' %}

{% block body %}
    {{ sg_datatable_render(datatable) }}
{% endblock %}
```
