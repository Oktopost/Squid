
### Contents
  * [Notes](#notes)
  * [Limit Functions](#limit-functions)
    * [limit($from, $count)](#limitfrom-count)
    * [limitBy($count)](#limitbycount)
    * [page($page, $pageSize)](#pagepage-pagesize)
    * [orderBy($column, $type = OrderBy::ASC)](#orderbycolumn-type--orderbyasc)

## Notes 

All the limit values are injection safe.

## Limit Functions

The next list of functions is available for:
- `ICmdSelect`
- `ICmdDelete`
- `ICmdUpdate`


### limit($from, $count)

* ```$from``` Query offset
* ```$count``` Maximum number of elements to select

```php
$select->limit(10, 2);
// SELECT ... LIMIT 10, 2
```


### limitBy($count)

* ```$count``` Maximum number of elements to select

```php
$select->limitBy(2);
// SELECT ... LIMIT 2
```


### page($page, $pageSize)

* ```$page``` Zero based index of the page to select.
* ```$pageSize``` Number of elements per page.

```php
$select->page(3, 10);
// SELECT ... LIMIT 30, 10
```

### orderBy($column, $type = OrderBy::ASC)

**NOTE:** The parameter `$column` is not used as a bind value, therefore the string 
passed here must be a valid SQL string and injection safe.

* ```$column``` Column, or array of columns to order by.
* ```$type``` Is the query will be descending or ascending.

```php
$select->orderBy('COUNT(*)', OrderBy::DESC);
// SELECT ... ORDER BY COUNT(*) DESC

$select->orderBy(['COUNT(*)', 'Name'], OrderBy::ASC);
// SELECT ... ORDER BY COUNT(*), Name
```