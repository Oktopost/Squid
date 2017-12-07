
### Contents
  * [Notes](#notes)
  * [Where Functions](#where-functions)
    * [byId($value)](#byidvalue)
    * [byField($field, $value)](#byfieldfield-value)
    * [byFields($fields, $values)](#byfieldsfields-values)
    * [where($exp, $bind = false)](#whereexp-bind--false)
    * [whereIn($field, $values, $negate = false)](#whereinfield-values-negate--false)
    * [whereNotIn($field, $values)](#wherenotinfield-values)
    * [whereExists(ICmdSelect $select, $negate = false)](#whereexistsicmdselect-select-negate--false)
    * [whereNotExists(ICmdSelect $select)](#wherenotexistsicmdselect-select)
  * [Additional Where Functions](#additional-where-functions)
    * [whereBetween(string $field, $greater, $less)](#wherebetweenstring-field-greater-less)
    * [whereNotEqual(string $field, $value))](#wherenotequalstring-field-value)
    * [whereLess(string $field, $value)](#wherelessstring-field-value)
    * [whereLessOrEqual(string $field, $value)](#wherelessorequalstring-field-value)
    * [whereGreater(string $field, $value)](#wheregreaterstring-field-value)
    * [whereGreaterOrEqual(string $field, $value)](#wheregreaterorequalstring-field-value)

## Notes 

All the value parameters are always bind and therefore are injection safe. 
for example the expression:
```php
$select->where('Id', "= '' or true");
```
will result in:
```sql
SELECT /*...*/ WHERE Id = ?
```
With bind values `"= '' or true"`

However any string passed as the field name or expression must be safe.
To avoid injections, instead of:
```php
$select->where("Id = $value");
```
You should use:
```php
$select->byField("Id", $value);
// OR
$select->where("Id = ?", [$value]);
```



## Where Functions

The next list of functions is available for:
- `ICmdSelect`
- `ICmdDelete`
- `ICmdUpdate`


### byId($value)

* ```$value``` Any scalar value, or a non empty array of scalar values

This function is identical to calling ```byField('Id', $value)```


```php
$select->byId(14);
// SELECT ... WHERE Id = 14

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

### byFields($fields, $values)

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
// SELECT ... WHERE (Name = 'Jhon' OR LastName = 'Jhonson')
```

To pass `false` as the bind value, set the `bind` parameter to `[false]`

```
 $select->where('IsDeleted = ?', [false]);

// SELECT ... WHERE IsDeleted = 0
```

### whereIn($field, $values, $negate = false)

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

### whereNotIn($field, $values)

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


### whereNotIn($field, $values);

Alias to ```whereNotIn($field, $values, true)``` 

### whereExists(ICmdSelect $select, $negate = false)

* ```$select``` The sub-query to check
* ```$negate``` If true, `NOT EXISTS` statement is used instead of `EXISTS`

```php
$select->whereExists($subQuery);
// SELECT ... WHERE EXISTS (SELECT ... )
```

### whereNotExists(ICmdSelect $select)	

Alias to ```whereExists(ICmdSelect $select)``` 


## Additional Where Functions

This list of function is present only in the `ICmdSelect` interface


### whereBetween(string $field, $greater, $less)

* ```$field``` Field name to compare
* ```$greater``` Scalar value the field must be greater of
* ```$less``` Scalar value the field must be less then.

```php
$select->whereBetween('Age', 10, 20);
// SELECT ... WHERE Age BETWEEN 10 AND 20
```

### whereNotEqual(string $field, $value)

* ```$field``` Field name to compare
* ```$value``` Scalar value to compare to.

```php
$select->whereNotEqual('Age', -1);
// SELECT ... WHERE Age != -1
```

### whereLess(string $field, $value)

* ```$field``` Field name to compare
* ```$value``` Scalar value to compare to.

```php
$select->whereLess('Age', 60);
// SELECT ... WHERE Age < 60
```

### whereLessOrEqual(string $field, $value)

* ```$field``` Field name to compare
* ```$value``` Scalar value to compare to.

```php
$select->whereLessOrEqual('Age', 80);
// SELECT ... WHERE Age <= 80
```

### whereGreater(string $field, $value)

* ```$field``` Field name to compare
* ```$value``` Scalar value to compare to.

```php
$select->whereLess('Age', 20);
// SELECT ... WHERE Age > 20
```

### whereGreaterOrEqual(string $field, $value)

* ```$field``` Field name to compare
* ```$value``` Scalar value to compare to.

```php
$select->whereLess('Age', 25);
// SELECT ... WHERE Age >= 25
```