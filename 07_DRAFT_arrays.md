# Arrays (that aren't arrays at all)

By now we've seen them a few times and already did some useful things with them: Arrays.

`array` is one of PHP's core types, certainly it's most commonly used data structure and also one of it's major problems. The thing is, PHP arrays aren't arrays at all.

What exactly is an array? It's a data structure that represents a sequence of values of the same type, it preserves the order of its values and has a fixed length. PHP arrays don't guarantee any of that.

PHP array are more like hash maps in other languages. They have a set of "keys" which can be strings or integers and each key has a value associated with it. If all keys are integers and are consecutive numbers, PHP arrays behave similar to real arrays, except that their size is not fixed. But if the keys are out of order, or some keys are strings, PHP arrays behave more like hash maps.

If you contruct an array only with it's values, it becomes a numerically indexed array. All keys will be integers and will start with `0`.

```php
$arr = [1, 2, 3, 4];
var_dump($arr);
/* prints:
array(4) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(3)
  [3]=>
  int(4)
}
```

However, we can also explicitly tell PHP what keys to use:

```
$arr = [
  3 => 1,
  "2" => 2,
  0 => 3,
  "1" => 4,
  "a" => 5
];

var_dump($arr);
/* prints:
array(5) {
  [3]=>
  int(1)
  [2]=>
  int(2)
  [0]=>
  int(3)
  [1]=>
  int(4)
  ["a"]=>
  int(5)
}
*/
```

Now, the array has the same values but the keys are no longer in ascending order. Also note, how PHP has converted some keys into integers although we specified two of them as strings. PHP will try to convert any array key into an integer. Only if that conversion does not work, it will leave it as a string as it did with th `"a"` key.
