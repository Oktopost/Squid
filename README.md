[![Build Status](https://travis-ci.org/Oktopost/Squid.svg?branch=master)](https://travis-ci.org/Oktopost/Squid)

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


# Basic Configuration
```php
use Squid\MySql;

$mysql = new MySql();
$mysql->config()
	->addConfig(
	'connection_name',
	[
		'db'	=> 'db_name',
		'host'	=> 'localhost',
		'pass'	=> 'password',
		'user'	=> 'user_name'
	]);

// Aquire new connector object
$connector = $mysql->getConnector('connection_name');

// Aquiring commands
$select = $connector->select();
$insert = $connector->insert();
$delete = $connector->delete();
```

# Select
## Columns

### column method
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

### columns method
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


## From
### from method
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

# Where Clause
The where clause commands are available in select, insert, update, upsert and delete commands.

## where method
```php
public function where($exp, $bind = false)
```

Provide any costume where expression with optional bind parameters.

_Example:_
```php
$select1->where('1 + 1 = ?', 2);
$select2->where('Table.SomeFieldName = ? - ?', [3, 1]);
$delete->where('NOW() > DATE(NOW())');
```
Will result respectively in:
```sql
SELECT * WHERE 1 + 1 = 2
SELECT * WHERE Table.SomeFieldName = 3 - 1
DELETE * WHERE NOW() > DATE(NOW())
```

## byField method
```php
public function byField($field, $value) 
```
Search for field = value or, if value is an array, where field IN (values)

_Example:_
```php
$select1->byField('Name', ['Jhon']);
$select1->byField('ROUND(Price)', [23]);
$select1->byField('ID', [2, 3]);
```
Will result respectively in:
```sql
SELECT * WHERE Name = 'Jhon'
SELECT * WHERE ROUND(Price) = 23
SELECT * WHERE ID IN (2, 3)
```
