# Getting Started With SgDatatablesBundle

This Bundle integrates the jQuery DataTables 1.10.x plugin into your Symfony application.

## Your First Datatable

### Step 1: 

### Step 2: Registering your Datatable as a Service

### Step 3: Add controller actions

```php
/**
 * @Route("/", name="post_index")
 * @Method("GET")
 *
 * @return Response
 */
public function indexAction()
{
    $datatable = $this->get('app.datatable.post');
    $datatable->buildDatatable();

    return $this->render('post/index.html.twig', array(
        'datatable' => $datatable,
    ));
}

/**
 * @param Request $request
 *
 * @Route("/results", name="post_results")
 * @Method("GET")
 *
 * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
 */
public function indexResultsAction(Request $request)
{
    $isAjax = $request->isXmlHttpRequest();

    if ($isAjax) {
        $responseService = $this->get('sg_datatables.response');
        // change now to POST request type if needed; default: 'GET'
        //$responseService->setType('POST');
        $datatableQuery = $responseService->getDatatableQuery();
        $datatableQuery->buildQuery();

        return $responseService->getResponse();
    }

    return new Response('Bad request.', 400);
}
```

### Step 4: Create your index.html.twig

```html
{% extends '::base.html.twig' %}

{% block body %}
    {{ sg_datatable_render(datatable) }}
{% endblock %}
```
