# Caching

1. [Using cache with Datatable](#1-using-cache-with-datatable)
2. [Doctrine configuration](#2-doctrine-configuration)
3. [Using Doctrine Cache for advanced cases](#3-using-doctrine-cache-for-advanced-cases)

It is possible to cache query and its result using Doctrine cache. Beside Doctrine Second Level Cache accessible through
QueryBuilder there is regular cache available through Doctrine Query. Below example shows how to setup usage of cache on
Doctrine Query when creating response for DataTable.

## 1. Using cache with Datatable

``` php
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

The signature of methods is 100% compatible with signatures of similar methods on Doctrine Query object.

## 2. Doctrine configuration

Under the hood `use*Cache` methods parameters are passed to Doctrine Query object when one is created and ready to be
executed. To use this kind of caching you need to configure doctrine cache as it is described in
[Doctrine configuration section in Symfony documentation](http://symfony.com/doc/current/reference/configuration/doctrine.html#caching-drivers).

Besicly all you need is to configure `metadata_cache_driver` and `query_cache_driver` to use query cache and
`result_cache_driver` to use result cache. Without use of any other bundles you can use those drivers:
* array,
* apc,
* apcu,
* memcache,
* memcached,
* redis,
* wincache,
* zenddata,
* xcache 
* service

The most basic configuration in `config.yml` might look like so:
``` yaml
doctrine:
  orm:
    entity_managers:
      default:
        connection: default
        # ...
        metadata_cache_driver:
          type: array
        query_cache_driver:
          type: array
        result_cache_driver:
          type: array
```

This is simplest possible configuration. All caches are setup to use simple arrays in memory. Depending on your application
specification it might or might not provide any advantage over non-cached queries, but it is subject of general caching
and not this documentation.

## 3. Using Doctrine Cache for advanced cases

Lets not reinvent the wheel and not use service type driver with our own cache providers implementations if there is
bundle for it. DoctrineCacheBundle provides impressive collection of ready providers for different technologies. To use
DoctrineCacheBundle first you need to add it to your project requirements. Easy step by step instruction is provided in
DoctrineCacheBundle documentation in [Installation](http://symfony.com/doc/current/bundles/DoctrineCacheBundle/installation.html)
section.

To use provider from DoctrineCacheBundle first you need to configure it. In your `config.yml` file you might do it like so:

``` yaml
doctrine_cache:
  providers:
    metadata_cache:
      file_system:
        extension: ".cache"
        directory: "%kernel.cache_dir%/doctrine/metadata"
    query_cache:
      file_system:
        extension: ".cache"
        directory: "%kernel.cache_dir%/doctrine/query"
    result_cache:
      file_system:
        extension: ".cache"
        directory: "%kernel.cache_dir%/doctrine/result"
```
In above listing 3 different cache providers are setup. All based on filesystem in different directories. Doctrine has
implementation of quite impressive list of built-in providers. Configuration of all is described in [Symfony specification](http://symfony.com/doc/current/bundles/DoctrineCacheBundle/reference.html).

Next step after configuration of providers is to enable usage of those in entity manager. Lets just change configuration
in `config.yml` file in doctrine>orm>entity_managers section:

``` yaml
doctrine:
  # ...
  orm:
    entity_managers:
      default:
        connection: default
        # ...
        metadata_cache_driver:
          type: service
          id: doctrine_cache.providers.metadata_cache
        query_cache_driver:
          type: service
          id: doctrine_cache.providers.query_cache
        result_cache_driver:
          type: service
          id: doctrine_cache.providers.result_cache
```

Above listing shows how 3 different cache drivers are set for default entity manager. Each driver is set as service, and
id of service points to doctrine cache provider setup earlier. It is not mandatory to use different cache providers for
each kind of cache driver, but might be good start for use of different kind of provider for different kind of data
(ex. Redis for results and filesystem for both metadata and queries).
