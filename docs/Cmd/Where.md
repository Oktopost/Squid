
### byId($value)

* ```$value``` Any scalar value, or a non empty array of scalar values

This function is identical to calling ```byField('Id', $value)```


```php
$select->byId(14);
// SELECT ... WHERE Id = 15

$select->byId([1, 2, 3]);
// SELECT ... WHERE Id IN (1, 2, 3)
```

### byField($field, $value)

* ```$feild``` Fields name or any valid mysql expression that can be followed by ```=``` sign or ```IN``` keyword.
* ```$value``` Any scalar value, or a non empty array of scalar values

```php
$select->byField('Name', 'Jhon');
// SELECT ... WHERE Name = 'Jhon'

$select->byField('Name', ['Jhon', 'Ron']);
// SELECT ... WHERE Name IN ('Jhon', 'Ron')

$select->byField('SUBSTR(Name, 3)', 'Ron');
// SELECT ... WHERE SUBSTR(Name, 3) = 'Ron'
```

### byFields($fields, $value)

* First case
	* ```$feilds``` Numeric array of fields names or valid mysql expressions
	* ```$values``` Array of the same size as ```$feilds```, where each value is compared to the ```$field``` under the same index
* Or
	* ```$feilds``` Assoc array where key is the field name or mysql expression to compare, and it's value is a scalar value (or array of scalar values) to compare to.
	* ```$values``` In this case ```$values``` parameter is ignored.

```php
$select->byFields(['Name', 'Age'], ['Jhon', 13]);
// SELECT ... WHERE Name = 'Jhon' AND Age = 13

$select->byField([
	'Name'	=> 'Jhon', 
	'Age'	=> [13, 14, 15]
]);
// SELECT ... WHERE Name = 'Jhon' AND Age IN (13, 14, 15)
```

### where($exp, $bind = false)

* ```$exp``` Expression to execute.
* ```$bind``` Bind parameters for the expression. Either a single scalar value, or array of multiple values. 

```php
$select->where('(Name = ? OR LastName = ?)', ['Jhon', 'Jhonson']);
// SELECT ... WHERE (Name = 'Jhon' OR Age = 'Jhonson')
```

To pass `false` as the bind value, set the `bind` parameter to `[false]`

```
 $select->where('IsDeleted = ?', [false]);

// SELECT ... WHERE IsDeleted = 0
```

### whereIn($field, $values, $negate = false);

* Single column comparison
	* ```$field``` Field name or expression to match given set.
	* ```$values``` Array of scalar values to compare to, or another `ICmdSelect` object. 
	* ```$negate``` If true, `NOT IN` is used.
* Vector comparison
	* ```$field``` Array of fields to match a set of vectors. 
	* ```$values``` Array of vectors that must much the `$field` array, or a `ICmdSelect` object to treat as ths sub-query.
	* ```$negate``` If true, `NOT IN` is used.
		

```php
$select->whereIn('Name', ['Jhon', 'Ron']);
// SELECT ... WHERE Name IN ('Jhon', 'Ron')

$select->whereIn(['FirstName', 'LastName', [['Jhon', 'Jhonson'], ['Ron', 'Ronson']);
// SELECT ... WHERE (FirstName, LastName) IN (('Jhon', 'Jhonson'), ('Ron', 'Ronson'));


$select->whereIn('Name', $subQuery);
// SELECT ... WHERE Name IN (SELECT ... )
```

### whereNotIn($field, $values);

Alias to ```whereNotIn($field, $values, true)``` 

### whereExists(ICmdSelect $select, $negate = false)

* ```$select``` The sub-query to check
* ```$negate``` If true, `NOT EXISTS` statement is used instead of `EXISTS`

```php
$select->whereExists($subQuery);
// SELECT ... WHERE EXISTS (SELECT ... )
```

### whereNotExists($field, $values);

Alias to ```whereExists(ICmdSelect $select)``` 