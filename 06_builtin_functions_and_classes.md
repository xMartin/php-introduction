# Builtin Functions and Classes

PHP comes with a pretty large set of features that are already built into it. Most of them are available as plain functions that can be called from anyhwere in your code. This is a big difference to languages where the standard library is completely organised into modules and classes. When PHP first became really popular, object oriented programming was not nearly as widely adopted as today and PHP adopted the style of the C programming language that also uses globally available functions. Later, mostly after PHP 5 was widely is use, some of the core functionality was also made available via classes, the probably most common example being the `DateTime` class.

In today's PHP, the "old way" of just calling functions is still available and it's often the only way to access a feature of the PHP runtime. Some things have also gotten object oriented API's (like `DateTime`) and some new features are only available as object oriented APIs like the classes from the [SPL (Standard PHP Library)](http://php.net/manual/en/book.spl.php).

## Builtin Functions

In the previous chapter, we already encountered `array_map()` and `usort()`. Both are functions that provide common operations on arrays. The PHP manual has a complete list of [PHP's array functions](http://php.net/manual/en/ref.array.php).

A common source of errors and annoyance is that the arguments of these functions are not always consistant. For axample, `array_map()` expects the first argument to be a function and the second to be an array while `usort()` expects them the other way around. `array_reduce()` also expects the callback function as the second argument.

The reason vof this is that for a long time, people have added features to PHP without worrying about consistency of the APIs and to maintain backwards compatibiltiy this can't be changed easily anymore. You will need to pay close attention to the order of arguments for PHP's builtin functions.

### String functions

PHP has a lot of functions to handle strings. The problem with them is that most of them don't work well with Unicode strings. Take `substr()` for example, it's supposed to extract a part of a string but when we use it with something like German umlauts, it messes up the characters:

```php
$str = "äöü";
echo substr($str, 0, 1) . PHP_EOL; //echoes "�" instead of the correct "ä"
echo mb_subtsr($str, 0, 1, "utf-8"); //echoes "ä" correctly with "utf-8" as encoding argument
```

When using string functions with inout that potentially has Unicode characters in it, always use the `mb_` version of it. By default, you should use "utf-8" as your encoding throughout your application to avoid any issues cause by not matching encodings.

The `mb_` string functions are provided by a PHP extension called "mbstring", it is usually installed by default.

## More functions

For details on how to use all these builtin functions, consult the manual page for that function. The explanations there are usually pretty good. Here's a few functions that are used very often and that you should definitely read about:

* Array functions
  * `array_map()`
  * `sort()`, `usort()` and `ksort()`
  * `array_filter()`
  * `array_push()`, `array_pop()`, `array_shift()` and `array_unshift()`
  * `array_key_exists()`
  * `array_merge()`
  * `array_slice()`
  * `count()`
* String functions
  * `mb_substr()`
  * `mb_strpos()`
  * `mb_strtolower()` and `mb_strtoupper()`
  * `sprintf()`
  * `trim()`
* Misc. functions
 * `explode()` and `implode()`
 * `isset()`, `empty()` and `is_null()`

There are many more and this list is by no means complete but it will get you started with some useful features of PHP.
