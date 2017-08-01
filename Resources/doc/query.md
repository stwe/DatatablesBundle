# The Doctrine Query

1. [Customize the Doctrine Query](#1-customize-the-doctrine-query)
2. [Subqueries](#2-subqueries)

## 1. Customize the Doctrine Query

The query is automatically generated and contains the fields of the Datatables class. 
Of course it is sometimes necessary to customize your query. This can be done in the **controller action**.

Suppose your `PostEntity` has a `createdBy` ManyToOne association:

``` php
// AppBundle\Entity\Post.php

class Post
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    // ...

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     */
    private $createdBy;
    
    // ...
}
```

Now you can view all posts created by `root`. The additional `where statement` now works like a filter.

``` php
public function indexAction(Request $request)
{
    // ...

    if ($isAjax) {
        $responseService = $this->get('sg_datatables.response');
        $responseService->setDatatable($datatable);

        $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
        $datatableQueryBuilder->buildQuery();

        //dump($datatableQueryBuilder->getQb()->getDQL()); die();
        
        /** @var QueryBuilder $qb */
        $qb = $datatableQueryBuilder->getQb();
        $qb->andWhere('createdBy.username = :username');
        $qb->setParameter('username', 'root');
        
        //Or Using Base Query Implementation. This wiil 
        $criteria = Criteria::create();
        $criteria->andWhere(Criteria::expr()->eq('createdBy.username', 'root'));
        $datatableQueryBuilder->setCriteria($criteria);

        return $responseService->getResponse();
    }

    // ...
}
```

## 2. Subqueries

Sometimes it is needed to count the number of rows or concatenate multiple fields.
You can do this in your **DatatablesClass** with subqueries.

``` php
$this->columnBuilder
    ->add('full_name', Column::class, array(
        'title' => 'Full name',
        'dql' => "CONCAT(createdBy.firstname, ' ', createdBy.lastname)",
        'searchable' => true,
        'orderable' => true,
    ))
    // If you want to use a subquery, please put subquery between parentheses and subquery aliases between braces.
    // Note that subqueries cannot be search with a "LIKE" clause.
    ->add('post_count', Column::class, array(
        'title' => 'User posts count',
        'dql' => '(SELECT COUNT({p}) FROM AppBundle:Post {p} WHERE {p}.createdBy = createdBy.id)',
        'searchable' => true,
        'orderable' => true,
    ))
;
```
