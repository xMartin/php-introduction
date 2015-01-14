# Builtin Functions and Classes

PHP comes with a pretty large set of features that are already built into it. Most of them are available as plain functions that can be called from anyhwere in your code. This is a big difference to languages where the standard library is completely organised into modules and classes. When PHP first became really popular, object oriented programming was not nearly as widely adopted as today and PHP adopted the style of the C programming language that also uses globally available functions. Later, mostly after PHP 5 was widely is use, some of the core functionality was also made available via classes, the probably most common example being the `DateTime` class.

In today's PHP, the "old way" of just calling functions is still available and it's often the only way to access a feature of the PHP runtime. Some things have also gotten object oriented API's (like `DateTime`) and some new features are only available as object oriented APIs like the classes from the [SPL (Standard PHP Library)](http://php.net/manual/en/book.spl.php).

## Builtin Functions

In the previous chapter, we already encountered `array_map()` and `usort()`. Both are functions that provide common operations on arrays. The PHP manual has a complete list of [PHP's array functions](http://php.net/manual/en/ref.array.php).

A common source of errors and annoyance is that the arguments of these functions are not always consistant. For axample, `array_map()` expects the first argument to be a function and the second to be an array while `usort()` expects them the other way around. `array_reduce()` also expects the callback function as the second argument.

The reason vof this is that for a long time, people have added features to PHP without worrying about consistency of the APIs and to maintain backwards compatibiltiy this can't be changed easily anymore. You will need to pay close attention to the order of arguments for PHP's builtin functions.
