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
Will result approprietly in:
```sql
SELECT * FROM Table
SELECT * FROM Table a
```
