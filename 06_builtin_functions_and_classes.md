# Builtin Functions and Classes

PHP comes with a pretty large set of features that are already built into it. Most of them are available as plain functions that can be called from anyhwere in your code. This is a big difference to languages where the standard library is completely organised into modules and classes. When PHP first became really popular, object oriented programming was not nearly as widely adopted as today and PHP adopted the style of the C programming language that also uses globally available functions. Later, mostly after PHP 5 was widely is use, some of the core functionality was also made available via classes, the probably most common example being the [`DateTime`][DateTime] class.

In today's PHP, the "old way" of just calling functions is still available and it's often the only way to access a feature of the PHP runtime. Some things have also gotten object oriented API's (like [`DateTime`][DateTime]) and some new features are only available as object oriented APIs like the classes from the [SPL (Standard PHP Library)](http://php.net/manual/en/book.spl.php).

## Builtin Functions

In the previous chapter, we already encountered [`array_map()`][array_map] and [`usort()`][usort]. Both are functions that provide common operations on arrays. The PHP manual has a complete list of [PHP's array functions](http://php.net/manual/en/ref.array.php).

A common source of errors and annoyance is that the arguments of these functions are not always consistant. For axample, [`array_map()`][array_map] expects the first argument to be a function and the second to be an array while [`usort()`][usort] expects them the other way around. [`array_reduce()`][array_reduce] also expects the callback function as the second argument.

Another inconsistency is in the function names. Some are written with underscores, others in one word, forexample [`get_class()`][get_class] and [`gettype()`][gettype].

The reason vof this is that for a long time, people have added features to PHP without worrying about consistency of the APIs and to maintain backwards compatibiltiy this can't be changed easily anymore. You will need to pay close attention to the order of arguments for PHP's builtin functions.

### String functions

PHP has a lot of functions to handle strings. The problem with them is that most of them don't work well with Unicode strings. Take [`substr()`][substr] for example, it's supposed to extract a part of a string but when we use it with something like German umlauts, it messes up the characters:

```php
$str = "äöü";
echo substr($str, 0, 1) . PHP_EOL; //echoes "�" instead of the correct "ä"
echo mb_subtsr($str, 0, 1, "utf-8"); //echoes "ä" correctly with "utf-8" as encoding argument
```

When using string functions with inout that potentially has Unicode characters in it, always use the `mb_` version of it, if there is one, and by default, you should use "utf-8" as your encoding throughout your application to avoid any issues cause by not matching encodings. If you have to receive or send data to external systems in ad different encoding, convert the encoding right at the edges of your application and keep everything as UTF-8 internally.

The `mb_` string functions are provided by a PHP extension called "mbstring", it is usually installed by default.

These are some of the most commonly used string functions. You should definitely read about those:

 * [`mb_substr()`][mb_substr]
 * [`mb_strpos()`][mb_strpos]
 * [`mb_strtolower()`][mb_strtolower] and [`mb_strtoupper()`][mb_strtolower]
 * [`sprintf()`][sprintf]
 * [`trim()`][trim], [`rtrim()`][rtrim] and [`ltrim()`][ltrim]
 * [`str_pad()`][str_pad]

### Array functions

Apart from string operation, handling arrays is hugely important in PHP. We will see in one of the chapters, why. Most array operations are available as functions in PHP. Here are some of the most used ones:

 * [`array_map()`][array_map]
 * [`sort()`][sort], [`usort()`][usort] and [`ksort()`][ksort]
 * [`array_filter()`][array_filter]
 * [`array_push()`][array_push], [`array_pop()`][array_pop], [`array_shift()`][array_shift] and [`array_unshift()`][array_unshift]
 * [`array_key_exists()`][array_key_exists]
 * [`array_merge()`][array_merge]
 * [`array_slice()`][array_slice]
 * [`count()`][count]
 * [`array_values()`][array_values] and [`array_keys()`][array_keys]
 * [`explode()`][explode] and [`implode()`][implode]

### File IO functions

Reading and writing files is a very common task in almost any programming language. PHP has several ways of doing that.

The simplest way is to use [`file_get_contents()`][file_get_contents] and [`file_put_contents()`][file_put_contents]. These two function read and write entire files from/into a string. They are very convenient but can cause problems with larger files because they load the entire contents of the file into memory at once.

There are also [`fopen()`][fopen], [`fread()`][fread], [`fwrite()`][fwrite] and [`fclose()`][fclose]. They provide far more control over how files are read/written. There will be a dedicated chapter on file IO where we explore these in more detail.

### Other functions

These are a few more commonly used functions that you'll probably need often:

 * [`isset()`][isset]
 * [`empty()`][empty]
 * [`get_class()`][get_class]
 * [`gettype()`][gettype]
 * [`is_a()`][is_a] and [the other `is_...()` functions](http://php.net/manual/en/ref.var.php)
 
## Builtin Classes

As object oriented style got more and more popular with PHP, new features have also gotten class based APIs. The most common one being [`DateTime`][DateTime] and its related classes.

### `DateTime`

A `DateTime` object represents a single point in time in PHP with up to microsecond precision. It enables you to reliably do date manipulations like "take NOW and add 423 days and 7 hours", correctly compare dates or describe and calculate time intervals like "the time span from NOW to the end of the year". Here's a few examples:

*Note: Don't forget to set the timezone for your application with [`date_default_timezone_set()`][date_default_timezone_set].*

```php
$towel_day = new DateTime("2015-05-25"); //http://en.wikipedia.org/wiki/Towel_Day
var_dump($towel_day);
/* prints:
object(DateTime)#1 (3) {
  ["date"]=>
  string(26) "2015-05-25 00:00:00.000000"
  ["timezone_type"]=>
  int(3)
  ["timezone"]=>
  string(13) "Europe/Berlin"
}
*/
```

As you can see, if you don't specify parts of the date, PHP set's them to 0. So, when you make a `DateTime` with no time information, PHP assumes, you mean "00:00:00" on that day for example.

For output it's usually a good idea to use a date format. `DateTime` objects have a `format()` method that does that:

```php
$towel_day_afternoon = new DateTime("2015-05-25 16:30:00");

//now print the date in a format that's common in the USA
echo $towel_day_afternoon->format("F j Y, g:h a") . PHP_EOL;

//now print it as an ISO-8601 timestamp, PHP has a shortcut for that
echo $towel_day_afternoon->format("c") . PHP_EOL;
```

```php
//How many days is it from the start of 2015 to Towel Day?
$towel_day = new DateTime("2015-05-25");
$january_first = new DateTime("2015-01-01");
$diff = $january_first->diff($towel_day);

//$diff is a DateInterval object:
var_dump($diff);
/* prints:
object(DateInterval)#6 (15) {
  ["y"]=>
  int(0)
  ["m"]=>
  int(4)
  ["d"]=>
  int(23)
  ["h"]=>
  int(0)
  ["i"]=>
  int(0)
  ["s"]=>
  int(0)
  ["weekday"]=>
  int(0)
  ["weekday_behavior"]=>
  int(0)
  ["first_last_day_of"]=>
  int(0)
  ["invert"]=>
  int(0)
  ["days"]=>
  int(144)
  ["special_type"]=>
  int(0)
  ["special_amount"]=>
  int(0)
  ["have_weekday_relative"]=>
  int(0)
  ["have_special_relative"]=>
  int(0)
}
*/

//to print only the total number of days:
echo $diff->days . PHP_EOL;
*/

**When doing any arithmetic with time or date values, always use the `DateTime` (and related) classes. There are so many edge cases in date/time calculations that you certainly don't want to to it yourself.**


[isset]: http://php.net/manual/en/function.isset.php
[empty]: http://php.net/manual/en/function.empty.php
[get_class]: http://php.net/manual/en/function.get-class.php
[gettype]: http://php.net/manual/en/function.gettype
[is_a]: http://php.net/manual/en/function.is-a.php

[array_map]: http://php.net/manual/en/function.array-map.php
[sort]: http://php.net/manual/en/function.sort.php
[usort]: http://php.net/manual/en/function.usort.php
[ksort]: http://php.net/manual/en/function.ksort.php
[array_filter]: http://php.net/manual/en/function.array-filter.php
[array_push]: http://php.net/manual/en/function.array-push.php
[array_pop]: http://php.net/manual/en/function.array-pop.php
[array_shift]: http://php.net/manual/en/function.array-shift.php
[array_unshift]: http://php.net/manual/en/function.array-unshift.php
[array_key_exists]: http://php.net/manual/en/function.array-key-exists.php
[array_merge]: http://php.net/manual/en/function.array-merge.php
[array_slice]: http://php.net/manual/en/function.array-slice.php
[count]: http://php.net/manual/en/function.count.php
[array_values]: http://php.net/manual/en/function.array-values.php
[array_keys]: http://php.net/manual/en/function.array-keys.php
[explode]: http://php.net/manual/en/function.explode.php
[implode]: http://php.net/manual/en/function.implode.php
[array_reduce]: http://php.net/manual/en/function.array-reduce.php

[substr]: http://php.net/manual/en/function.substr.php
[mb_substr]: http://php.net/manual/en/function.mb-substr.php
[mb_strpos]: http://php.net/manual/en/function.mb-strpos.php
[mb_strtolower]: http://php.net/manual/en/function.mb-strtolower.php
[mb_strtoupper]: http://php.net/manual/en/function.mb-strtoupper.php
[sprintf]: http://php.net/manual/en/function.sprintf.php
[trim]: http://php.net/manual/en/function.trim.php
[rtrim]: http://php.net/manual/en/function.rtrim.php
[ltrim]: http://php.net/manual/en/function.ltrim.php
[str_pad]: http://php.net/manual/en/function.str_pad.php

[file_get_contents]: http://php.net/manual/en/function.file-get-contents.php
[file_put_contents]: http://php.net/manual/en/function.file-put-contents.php
[fopen]: http://php.net/manual/en/function.fopen.php
[fread]: http://php.net/manual/en/function.fread.php
[fwrite]: http://php.net/manual/en/function.fwrite.php
[fclose]: http://php.net/manual/en/function.fclose.php

[isset]: http://php.net/manual/en/function.isset.php
[empty]: http://php.net/manual/en/function.empty.php
[get_class]: http://php.net/manual/en/function.get_class.php
[gettype]: http://php.net/manual/en/function.gettype.php
[is_a]: http://php.net/manual/en/function.is_a.php

[DateTime]: http://php.net/manual/en/class.datetime.php
[date_default_timezone_set]: http://php.net/manual/en/function.date-default-timezone-set.php
