# Caching

It is possible to cache query and its result using Doctrine cache. Beside Doctrine Second Level Cache accessible through
QueryBuilder there is regular cache available through Doctrine Query. Below example shows how to setup usage of cache on
Doctrine Query when creating response for DataTable.

```php
<?php
// ...
if ($isAjax) {
    $responseService = $this->get('sg_datatables.response');
    $responseService->setDatatable($datatable);

    $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
    $datatableQueryBuilder->buildQuery();

    $datatableQueryBuilder->useQueryCache(true);            // (1)
    $datatableQueryBuilder->useCountQueryCache(true);       // (2)
    $datatableQueryBuilder->useResultCache(true, 60);       // (3)
    $datatableQueryBuilder->useCountResultCache(true, 60);  // (4)

    return $responseService->getResponse();
}
// ...
```

Above listing is copied from installation documentation. It is part of action of controller responsible for displaying
datatable. As you may easily see some lines are marked (1) through (4). Meaning of those lines is as follows:
1. turn on query cache for records retrieval
2. turn on query cache for records counting
3. turn on result cache for records retrieval
4. turn on result cache for records counting

Under the hood those parameters are passed to Doctrine Query object when one is created and ready to be executed. To use 
this kind of caching you need to configure doctrine cache as it is described in [Doctrine configuration section in Symfony documentation](http://symfony.com/doc/current/reference/configuration/doctrine.html#caching-drivers).

The signature of methods is 100% compatible with signatures of similar methods on Doctrine Query object.