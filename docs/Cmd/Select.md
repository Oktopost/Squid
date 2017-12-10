
### Contents
  * [Notes](#notes)
  * [Methods](#methods)
    * [distinct($distinct = true)](#distinctdistinct--true)
    * [from($table, $alias = false)](#fromtable-alias--false)
    * [join($table, $alias, $condition, $bind = false)](#jointable-alias-condition-bind--false)
    * [leftJoin($table, $alias, $condition, $bind = false, $outer = false)](#leftjointable-alias-condition-bind--false-outer--false)
    * [rightJoin($table, $alias, $condition, $bind = false, $outer = false)](#rightjointable-alias-condition-bind--false-outer--false)
    * [groupBy($column, $bind = false)](#groupbycolumn-bind--false)
    * [withRollup($withRollup = true)](#withwollupwithrollup--true)
    * [having($exp, $bind = false)](#havingexp-bind--false)
    * [union(IMySqlCommandConstructor $select, $all = false)](#unionimysqlcommandconstructor-select-all--false)
    * [unionAll(IMySqlCommandConstructor $select)](#unionallimysqlcommandconstructor-select)
    * [forUpdate($forUpdate = true)](#forupdateforupdate--true)
    * [lockInShareMode($lockInShareMode = true)](#lockinsharemodelockinsharemode--true)
    
     


## Notes 

All of this functions are available only for the `ICmdSelect` and `ICmdDirect` interfaces.


## Methods

### distinct($distinct = true)

* ```$value``` If set to true the flag is added, otherwise it's removed.

Add the distinct keyword to the start of the columns clause. 

```php
$res = $select
	->disitnct()
	->columns('Age', 'COUNT(*) as cnt');
	
// SELECT DISTINCT Age, COUNT(*) ...
```


### from($table, $alias = false)

* ```$table``` Table name, or a subquery to select from. Can also be any valid mysql expression for the FROM clause.
* ```$alias``` Optional alias for this table. The alias is required only when executing a subquery. 

Note that any consequent call to this method will override the previously set table. If you wish to select from
more then one table, use the join methods.



```php
$res = $select
	->from('Data', 'd');
	
// SELECT ... FROM Data d ...
 
 $res = $select
	->from($subQuery, 'a');
	
// SELECT ... FROM (SELECT ... ) a ...
```


### join($table, $alias, $condition, $bind = false)

* ```$table``` Table name, or a subquery to join.
* ```$alias``` Table/Subquery alias.
* ```$condition``` The join condition. The condition must be an SQL safe string. Any values should be passed in the `$bind` parameter
* ```$bind``` Any bind parameters for the condition. Can be an array, or a single non false value.

Add an inner join expression to the query. 

```php
$select
	->from('Data', 'd')
	->join('Log', 'l', 'l.UserId = d.Id AND l.Type = ?', 'data-log');
	
// SELECT ... 
// FROM Data d 
//		JOIN Log l ON 
//			l.UserId = d.Id AND l.Type = 'data-log'
```


### leftJoin($table, $alias, $condition, $bind = false, $outer = false)

* ```$table``` Table name, or a subquery to join.
* ```$alias``` Table/Subquery alias.
* ```$condition``` The join condition. The condition must be an SQL safe string. Any values should be passed in the `$bind` parameter
* ```$bind``` Any bind parameters for the condition. Can be an array, or a single non false value.
* ```$outer``` If set to true, `LEFT OUTER JOIN` will be used.

This method is identical to the `join`  method, but instead of `JOIN` uses `LEFT JOIN`


### rightJoin($table, $alias, $condition, $bind = false, $outer = false)

* ```$table``` Table name, or a subquery to join.
* ```$alias``` Table/Subquery alias.
* ```$condition``` The join condition. The condition must be an SQL safe string. Any values should be passed in the `$bind` parameter
* ```$bind``` Any bind parameters for the condition. Can be an array, or a single non false value.
* ```$outer``` If set to true, `RIGHT OUTER JOIN` will be used.

This method is identical to the `join`  method, but instead of `JOIN` uses `RIGHT JOIN`


### groupBy($column, $bind = false) 

* ```$column``` Single or an array of columns to group by.
* ```$bind``` Any bind parameters for the condition. Can be an array, or a single non false value.

Add a single or on array of columns to the group by clause.

```php
$select
	->groupBy('Age')
	->groupBy(['COUNT(*) < ?'], 20);
	
// SELECT ... 
// GROUP BY
//		Age, 
//		COUNT(*) < 20
```


### withRollup($withRollup = true) 

* ```$withRollup``` If set to true, add the rollup flag, otherwise remove it.

Add the `WITH ROLLUP` keyword to the `GROUP BY` clause.

> `WITH ROLLUP`: https://dev.mysql.com/doc/refman/5.7/en/group-by-modifiers.html 

```php
$select
	->groupBy('Age')
	->withRollup();
	
// SELECT ... GROUP BY Age WITH ROLLUP ...  
```


### having($exp, $bind = false) 

* ```$exp``` Having expression to add.
* ```$bind``` Any bind parameters for the expression.

Add a single `HAVING` expression.

```php
$select
	->having('Age > ?', 25);
	
// SELECT ... 
// HAVING Age > 25 
// ...
```


### union(IMySqlCommandConstructor $select, $all = false)

* ```$select``` Subquery to union with.
* ```$all``` If true, use the `UNION ALL` expression.

Add a union expression to the query.

```php
$select
	->from('Data')
	->union($anotherSelect);
	
// SELECT ... 
// HAVING Age > 25 
// ...
```


### unionAll(IMySqlCommandConstructor $select)

Alias to `union($select, true)`


### forUpdate($forUpdate = true)

* ```$forUpdate``` If set to true, add the keyword, otherwise remove it.

Add the `FOR UPDATE` keyword to the query.\
This method will override any exiting lock in share mode flag.

```php
$select
	->from('Data')
	->forUpdate();
	
// SELECT ... FOR UPDATE
```

> [Mysql Documentation](https://dev.mysql.com/doc/refman/5.7/en/innodb-locking-reads.html)

### lockInShareMode($lockInShareMode = true)

* ```$lockInShareMode``` If set to true, add the keyword, otherwise remove it.

Add the `LOCK IN SHARE MODE` keyword to the query.\
This method will override any exiting lock for update flag.

```php
$select
	->from('Data')
	->lockInShareMode();
	
// SELECT ... LOCK IN SHARE MODE
```

> [Mysql Documentation](https://dev.mysql.com/doc/refman/5.7/en/innodb-locking-reads.html)