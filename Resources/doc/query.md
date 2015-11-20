# Query callbacks

You can apply additional `where` conditions to your query.

1. [WhereAll callback](#1-whereall-callback)
2. [WhereResult callback](#2-whereresult-callback)
3. [Get query](#3-get-query)

## 1. WhereAll callback

This is applied to all queries that are made and reduces the number of records that the user can access. 

```php
public function indexResultsAction()
{
    $datatable = $this->get('app.datatable.comment');
    $datatable->buildDatatable();

    $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

    $function = function($qb)
    {
        // $qb->andWhere("post.title = :p");
        $qb->andWhere("post_categories.title = :p");
        $qb->setParameter('p', 'Test');
    };

    $query->addWhereAll($function);

    return $query->getResponse();
}
```

## 2. WhereResult callback

This is only applied to the result set, but not the overall paging information.

```php
public function indexResultsAction()
{
    $datatable = $this->get('app.datatable.comment');
    $datatable->buildDatatable();

    $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

    $function = function($qb)
    {
        // $qb->andWhere("post.title = :p");
        $qb->andWhere("post_categories.title = :p");
        $qb->setParameter('p', 'Test');
    };

    $query->addWhereResult($function);

    return $query->getResponse();
}
```

## 3. Get query

```php
public function indexResultsAction()
{
    $datatable = $this->get('app.datatable.comment');
    $datatable->buildDatatable();

    $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
    $query->buildQuery();

    $qb = $query->getQuery();
    $qb->andWhere("post.visible = true");

    $query->setQuery($qb);

    return $query->getResponse(false); // important: $query->getResponse(false) instead $query->getResponse()
}
```
