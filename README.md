# Squid
Squid is a MySQL library

Example code:
```php
$select = $connector->select();
$select
	->column('a.*', 'b.Modified')
	->from('TableName', 'a')
	->leftJoin('AnotherTable', 'b', 'a.ID = b.TableNameID AND b.Status = ?', 'valid')
	->byField('b.Name', 'Jhon')
	->where('DATE(a.Created) > ?', new \DateTime());
	
$result = $select->query();

$modifiedAt = $result[0]['Modified'];
```

# Select

## From
**NOTE:** A select can have only one main table. Calling this method a second time on the same select object will replace
previous table selection.

```php
public function from($table, $alias = false)
```

Describe the main table you want to select from. This method can't be used for selecting from multiplay tables. To do so, use the _join_ method.

_Example:_
```php
$select1->from('Table');
$select2->from('Table', 'a');
```
Will result respectively in:
```sql
SELECT * FROM Table
SELECT * FROM Table a
```

## Column
```php
public function column(...$columns)
```

Set the columns to select.

_Example:_
```php
$select1->column('a', 'NOW()');
$select2->column('a.a', 'a.b');
```
Will result respectively in:
```sql
SELECT a, NOW()
SELECT a.a, a.b
```

## Columns
### Columns
```php
public function columns($columns, $table = false)
```
Set the columns to select using an array variable.

_Example:_
```php
$select1->columns(['a', 'NOW()']);
$select2->columns(['a', 'b'], 'a');
```
Will result respectively in:
```sql
SELECT a, NOW()
SELECT a.a, a.b
```
