
### Contents
  * [Notes](#notes)
  * [Column Methods](#column-methods)
    * [column(...$columns)](#columncolumns)
    * [columns($columns, $table = false)](#columnscolumns-table--false)
    * [columnsExp($columns, $bind = false)](#columnsexpcolumns-bind--false)
    * [columnAs($column, $alias)](#columnascolumn-alias)
    * [columnAsExp($column, $alias, $bind = false)](#columnasexpcolumn-alias-bind--false)

## Notes 
Only the values passed in the `$bind` parameter for methods `columnsExp` and `columnAsExp` - are injection safe,
all the other parameters must be valid and safe strings.

## Column Methods

The next list of functions is available only for `ICmdSelect` interface.


### column(...$columns)

* ```$columns``` Array of columns or valid MySQL queries to execute.

```php
$select->column('a', 'COUNT(*)');
// SELECT a, COUNT(*) ...
```

### columns($columns, $table = false)

* ```$columns``` Single column or array of columns to select. Can be either a column name or any other valid MySQL expression.
* ```$table``` If set, will be appended as the table alias before each column.

```php
$select->columns('a');
// SELECT a ...

$select->columns('a', 't');
// SELECT `t`.`a` ...

$select->columns(['a', 'COUNT(*)', 't.b']);
// SELECT a, COUNT(*), t.b ...
```

### columnsExp($columns, $bind = false)

* ```$columns``` Single column or array of columns to select. Can be either a column name or any other valid MySQL expression.
* ```$bind``` Bind values for the query.

```php
$select->columnsExp('CONCAT(?, Name)', [ 'Mr. ' ]);
// SELECT CONCAT('Mr. ', Name) ...
```

### columnAs($column, $alias)

* ```$column``` Single column or MySQL expression to select.
* ```$alias``` Must be a safe string.

Alias to `->column("$column as $alias")`

```php
$select->columnAs('u.Name', 'UserName');
// SELECT u.Name as UserName ...
```

### columnAsExp($column, $alias, $bind = false)

* ```$column``` Single column or MySQL expression to select.
* ```$alias``` Must be a safe string.
* ```$bind``` Bind values for the query.

Alias to `->column(["$column as $alias"], $bind)`

```php
$select->columnAsExp('CONCAT(?, Name)', 'Name', [ 'Mr. ' ]);
// SELECT CONCAT('Mr. ', Name) as Name ...
```