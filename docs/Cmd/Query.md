
### Contents
  * [Notes](#notes)
  * [Methods](#methods)
    * [query()](#query)
    * [queryAll($isAssoc = false)](#queryallisassoc--false)
    * [queryRow($isAssoc = false, $expectOne = true)](#queryrowisassoc--false-expectone--true)
    * [queryColumn($expectOne = true)](#querycolumnexpectone--true)
    * [queryScalar($default = false, $expectOne = true)](#queryscalardefault--false-expectone--true)
    * [queryInt($expectOne = true)](#queryintexpectone--true)
    * [queryBool($expectOne = true)](#queryboolexpectone--true)
    * [queryExists()](#queryexists)
    * [queryCount()](#querycount)
    * [queryBool($expectOne = true)](#queryboolexpectone--true)
    * [queryWithCallback($callback, $isAssoc = true)](#querywithcallbackcallback-isassoc--true)
    * [queryIterator($isAssoc = true)](#queryiteratorisassoc--true)
    * [queryMap($key = 0, $value = 1)](#querymapkey--0-value--1)
    * [queryMapRow($key = 0, $removeColumnFromRow = false)](#querymaprowkey--0-removecolumnfromrow--false)


## Notes 

All of this functions are available only for the `ICmdSelect` and `ICmdDirect` interfaces.


## Methods

### query()

Return an associative array of results.

```php
$res = $select
	->columns('Id', 'Name', 'COUNT(*) as cnt')
	->query();
	
// $res = 
// [ 
//	['Id' => 1, 'b' => 'John',	'cnt' => 3],
//	['Id' => 2, 'b' => 'Bjorn', 'cnt' => 12],
//	...
// ];
```


### queryAll($isAssoc = false)

* ```$isAssoc``` If true, return assoc array, otherwise return a numeric array.

Return an associative or numeric result.
If ```$isAssoc``` is true, this method will behave like `query()`.


```php
$res = $select
	->columns('Id', 'Name', 'COUNT(*) as cnt')
	->queryAll();
	
// $res = 
// [ 
//	[1, 'John',	3],
//	[2, 'Bjorn', 12],
//	...
// ];
```


### queryRow($isAssoc = false, $expectOne = true)

* ```$isAssoc``` If true, Associative array is returned.
* ```$expectOne``` If set to true, and more then one record is selected, an exception will be thrown.

Get only one row from the result set.
If the result set was empty, false will be returned.

```php
$select
	->columns('Id', 'Name', 'COUNT(*) as cnt')
	->limit(1);
	
$res = $select ->queryRow(true);
// $res = ['Id' => 1, 'b' => 'John', 'cnt' => 3]

$res = $select ->queryRow(false);
// $res = [2, 'Bjorn', 12]
```


### queryColumn($expectOne = true)

Get only one column from the result set

* ```$expectOne``` If set to true, and more then one column is selected, an exception will be thrown.

```php
$res = $select
	->columns('Id')
	->queryRow(true);
	
// $res = [1, 2, 3, ...]
```


### queryScalar($default = false, $expectOne = true)

Query only the first column of the first result

* ```$default``` Default value to return if the result set was empty.
* ```$expectOne``` If set to true, and more then one column OR row was seleted, an exception will be thrown.

```php
$res = $select
	->columns('Name')
	->queryScalar();
	
// $res = 'John'


$res = $select
	->columns('Name')
	->where('FALSE');
	->queryScalar(null);
	
// $res = null
```

### queryInt($expectOne = true)

Behaves like queryScalar, but the result is always cast to an integer.

If nothing was found and `$expectOne` if `false`, then `false` is returned.


### queryBool($expectOne = true)

Behaves like queryScalar, but the result is always cast to a boolean.

If nothing was found and `$expectOne` if `false`, then `false` is returned. 


### queryExists()

Create a `SELECT EXISTS (...)` expression, where the subquery, is the query generated from 
the subject on which queryExists was called. Result of this method is always a boolean value

```php
$res = $select
	->from('Data')
	->where('Id > ?', 25)
	->queryExists();

// SELECT EXISTS (SELECT * FROM Data WHERE Id > 25)
```

Note that if any column expression was specified, it will be persistent in the generated expression.


### queryCount()

Generate a `select count(*)` expression, based on the current query.

This function will behave differently based on the type of the target query

- For a **simple query**, any column expression will be replaced with a single `COUNT(*)`

```php
$res = $select
	->column('Id', 'Name')
	->from('Data')
	->where('Id > ?', 25)
	->queryCount();

// SELECT COUNT(*) FROM Data WHERE Id > 25
```

- For a **`GROUP BY`** expression, the columns expression will be replaced with a `COUNT(DISTINCT ...)` expression.

```php
$res = $select
	->column('MAX(Age)')
	->from('Data')
	->where('Id > ?', 25)
	->groupBy('Type')
	->queryCount();

// SELECT COUNT(DISTINCT MAX(Age)) FROM Data WHERE Id > 25
```

- For a **`UNION`** query or if the **`DISTINCT`** flag is present, a subquery is used:

```php
$res = $select
	->distinct()
	->column('Age')
	->from('Data')
	->where('Id > ?', 25)
	->queryCount();

// SELECT COUNT(*) FROM (SELECT DISTINCT Age FROM Data WHERE Id > 25) a


$res = $selectOldUsers
	->distinct()
	->column('Age')
	->from('Data_Old')
	->where('Id > ?', 25)
	->union($select)
	->queryCount();

// SELECT COUNT(*) 
// FROM (
//		SELECT DISTINCT Age FROM Data WHERE Id > 25 
//		UNION 
//		SELECT DISTINCT Age FROM Data_Old WHERE Id > 25)
// ) a
```


If none of the above fits, it's also possible to generate a query with `COUNT(...)` set explicitly 
and then invoke the `queryInt()` method to get desired behivor. 


### queryWithCallback($callback, $isAssoc = true)

* ```$callback``` A callable to invoke for each found row.
* ```$isAssoc``` If true, data passed to the callback will be an associative array, otherwise it will be a numeric array.

This method have additional behavior according to the return value of `$callback`:

* If, at any point, `false` is returned, the execution will be aborted and `queryWithCallback` will return `false`.
* If, at any point, `0` is returned, the execution will be aborted and `queryWithCallback` will return `true`.
* For any other return value, `queryWithCallback` will continue execution.


```php
// Assuming table Data:
// [ Id,	Name ]
// [ 1,		'a' ],
// [ 2, 	'b' ],
// [ 3,		'c' ]

$res = $select
	->from('Data')
	->orderBy('Id', OrderBy::ASC)
	->queryWithCallback(
		function ($row)
		{
			echo $row['Name']
		
			if ($row['Id'] == 2)
				return 0;
			
			if ($row['Id'] == 4)
				return false;
			
			return true;
		},
		true
	);

// $res will be true.
// And the output will be:
// a
// b
```

### queryIterator($isAssoc = true)

* ```$isAssoc``` If true, rows will be an associative array.

This method will hold a cursor the the database, meaning that no other queries should be executed until 
the iterator of this method is being used.

Note that this method is memory efficient. Only one record is created per each iteration.

```php

// Assuming table Data:
// [ Id,	Name ]
// [ 1,		'a' ],
// [ 2, 	'b' ],
// [ 3,		'c' ]

$it = $select
	->from('Data')
	->orderBy('Id', OrderBy::ASC)
	->queryIterator(true);
	
foreach ($it as $row)
{
	echo $it['Name'];
}

// The output will be:
// a
// b
// c
```

### queryMap($key = 0, $value = 1)

* ```$key``` The column that should be treated the the map key.
* ```$value``` The column that should be treated as value.

Return an array where key is the `$key` column, and value is the `$value` column.

Any value will override any previous value for same key, if such exists.

If `$key` or `$value` columns are not present, an error will be thrown.

If either `$key` or `$value` are strings, **both** of the will be treated as associative names, otherwise 
they will be treated as numeric indexes of the relevant columns. 

```php
// Assuming table Data:
// [ Id,	Name ]
// [ 1,		'a' ],
// [ 2, 	'b' ],
// [ 3,		'a' ]

$map = $select
	->from('Data')
	->queryMap(0, 1);
	

// $map = 
// [
//	'a' => 3, 
//	'b' => 2
// ]
```

Invoking `queryMap('Id', 'Name')` would produce the same result.

### queryMapRow($key = 0, $removeColumnFromRow = false)

* ```$key``` The column that should be treated the the map key.
* ```$removeColumnFromRow``` If true, the column `$key` will not be present in the value of the map.
 
This method behaves similar to `queryMap`, but instead of mapping a single column to a single key, it will
map the hole row to the specified key.

Return an array where key is the `$key` column, and value is the `$value` column.

Any row will override any previous row for same key, if such exists.

If `$key` column is not present, an error will be thrown.

If `$key` is a strings, row will be an assoc array, otherwise it will be a numeric array.

```php
// Assuming table Data:
// [ Id,	Name,	Age]
// [ 1,		'a',	20 ],
// [ 2, 	'b',	30 ],
// [ 3,		'c',	40 ]

$map = $select
	->from('Data')
	->queryMapRow('Id', true);
	

// $map = 
// [
//	1 => [ 'Name' => 'a', 'Age' => 20 ], 
//	2 => [ 'Name' => 'b', 'Age' => 30 ],
//	3 => [ 'Name' => 'c', 'Age' => 40 ]
// ]
```