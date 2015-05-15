---
title: Arrays
layout: default
---

By now we've seen them a few times and already did some useful things with them: Arrays.

`array` is one of PHP's core types, certainly it's most commonly used data structure and also one of it's major problems. The thing is, PHP arrays aren't arrays at all.

What exactly is an array? It's a data structure that represents a sequence of values of the same type, it preserves the order of its values and has a fixed length. PHP arrays don't guarantee any of that.

PHP array are more like hash maps in other languages. They have a set of "keys" which can be strings or integers and each key has a value associated with it. If all keys are integers and are consecutive numbers, PHP arrays behave similar to real arrays, except that their size is not fixed. But if the keys are out of order, or some keys are strings, PHP arrays behave more like hash maps.

If you construct an array only with it's values, it becomes a numerically indexed array. All keys will be integers and will start with `0`.

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

Now, the array has the same values but the keys are no longer in ascending order. Also note, how PHP has converted some keys into integers although we specified two of them as strings. PHP will try to convert any array key into an integer. Only if that conversion does not work, it will leave it as a string as it did with the `"a"` key.

We will talk about this implicit conversion of types [in the next chapter](08_DRAFT_type_juggling.md) by the way.

To summarize, an array in PHP is an ordered sequence of elements, each which has a key and a value. The key can be either an `int` or a `string` and the value can be of an any type. The order of the sequence does NOT depend on the keys.

The PHP manual page about arrays states the following right at the beginning:

> An array in PHP is actually an ordered map.

This is the closest thing to a precise name I could find for this data structure. Sometimes PHP arrays are also called "associative arrays" but I think "ordered map" better describes what is going on.

## The internal array pointer

Every PHP `array` has an internal cursor that points to the "current element" of the sequence. This is called the "internal array pointer". There are functions that work with this pointer and/or change its position.

```php

$arr = [
  'a' => 1,
  'b' => 2,
  'c' => 3,
  'd' => 4
];

//current() returns the value at the internal pointer's position
echo current($arr) . PHP_EOL; //prints "1";

// next() moves the internal pointer forward one element and then does the same as current()
echo next($arr) . PHP_EOL; //prints "2"
echo next($arr) . PHP_EOL; //prints "3"
echo next($arr) . PHP_EOL; //prints "4"
echo current($arr) . PHP_EOL; //prints "4"

//key() returns the key at the internal pointer's position
echo key($arr) . PHP_EOL; //prints "d"

//reset() puts the internal pointer back at the first element
reset($arr);

echo current($arr) . PHP_EOL; //prints "1"

```

Functions like [`current()`][current], [`next()`][next], [`reset()`][reset], [`key()`][key] and [`end()`][end] are useful when dealing with arrays, especially, if their length is not known. But keep in mind how the internal pointer position changes as you use them.

## Iteration over arrays

One of the most common things you'll do with arrays is to iterate over them, executing a piece of code with each of the array's elements. The basic way to do that is the `foreach` construct:

```php

$arr = [1, 2, 3, 4];

foreach ($arr as $value) {
  echo $value . PHP_EOL;
}

/* prints:
1
2
3
4
*/

```

You can also get the key and the value while iterating:

```php

$arr = [
  'a' => 1,
  'b' => 2,
  'c' => 3,
  'd' => 4
];

foreach ($arr as $key => $value) {
  echo $key . ': ' . $value . PHP_EOL;
}

/* prints:
a: 1
b: 2
c: 3
d: 4
*/
```

When using `foreach`, keep in mind that it changes the internal pointer's position. So use `reset()` after `foreach`, if you want to use functions like `current()` or `next()` after iterating. `foreach` itself always resets the pointer before starting the iteration so you can use it without manually resetting the array.

# Real, actual arrays

We talked about how an `array` in PHP is not actually an array data structure at all. It's a weird hybrid of various data structures and sometimes behaves unexpectedly. However, there is [`SplFixedArray`][SplFixedArray] which implements an actual array similar to other dynamic languages like Ruby or Python.

It has a fixed length and keys may only be integers. It still allows the values to be of multiple different types but that's a property that most dynamic languages share.

The main benefits of this class are the much more predictable behavior and speed. PHP's implementation of `SplFixedArray` is much faster than the one for `array`.

You can construct an `SplFixedArray` from an `array`:

```php
$arr = SplFixedArray::fromArray([1, 2, 3, 4]);
```

Or you can create one with a specific length that has `null` as values:

```php
$arr = new SplFixedArray(4);
```

`SplFixedArray` works with PHP's built in array function and also with `foreach`:

```php
$arr = SplFixedArray::fromArray([1, 2, 3, 4]);

echo current($arr) . PHP_EOL; //prints "1"
echo next($arr) . PHP_EOL; //prints "2"
echo current($arr) . PHP_EOL; //prints "2"
echo end($arr) . PHP_EOL; //prints "4"

foreach ($arr as $value) {
  echo $value . PHP_EOL;
}

/* prints:
1
2
3
4
*/

reset($arr);

echo current($arr) . PHP_EOL; //prints "1"
```

If you need to represent a sequence of values in your program, use `SplFixedArray`. It might seem more complicated but in fact is safer, faster and will cause you less trouble when you're debugging your code.

There are more data structures in PHP that provide better access to features of `array`. One of them is [SplOjectStorage][SplOjectStorage]. Its use cases are a bit beyond the scope of this tutorial but you should read the manual pages about it.


[current]: http://php.net/manual/en/function.current.php
[next]: http://php.net/manual/en/function.next.php
[reset]: http://php.net/manual/en/function.reset.php
[end]: http://php.net/manual/en/function.end.php
[key]: http://php.net/manual/en/function.key.php
[SplFixedArray]: http://php.net/manual/en/class.splfixedarray.php
[SplObjectStorage]: http://php.net/manual/en/class.splobjectstorage.php
