# Basic Syntax

PHP's syntax borrows some concepts and styles from other languages, mostly C, Perl and Java.

It uses "curly braces" (`{}`) to structure the code, variable names start with a `$` sign and statements
end with a semicolon.

Here's a piece of example PHP code that computes the first 10
[Fibonacci Numbers](http://en.wikipedia.org/wiki/Fibonacci_number) and prints them to the console.

You can save this code as `fibonacci.php` and run it with `php fibonacci.php`.

```php
<?php

function fibonacci($count)
{
    $n1 = 0;
    $n2 = 1;
    $n = 1;
    $numbers = [];
    
    for ($i = 0; $i < $count; $i++) {
        $numbers[] = $n;
        $n = $n1 + $n2;
        $n1 = $n2;
        $n2 = $n;
    }
    
    return $numbers;
}

echo implode(fibonacci(10), ", ") . PHP_EOL;
```

The output should look like this:

```
1, 1, 2, 3, 5, 8, 13, 21, 34, 55
```

I'll now go through that piece od code, explaining what each part does.

## The PHP Tag

```php
<?php
```

Every PHP file has to start with this line. Just put it right at the top every time. There may be no other characters before the PHP tag, not even whitespace.

## Function definitions

```php
function foo($bar, $batz)
{
    return $bar + $batz;
}
```

A function is a piece of code that is given a name and is reusable.

Functions have input values, called "arguments".
Here, the function `foo` has two arguments `$bar` and `$batz`.

Functions also have exactly one output value, it's called the "return value". This example function
returns the sum if its two arguments.

The function's "body", the code that it executes, is contained in curly braces. Because function
definitions are not statements, they do not end with a semicolon.


## Variables

```php
$n = 1;
```

Variable names in PHP start with a `$` sign and may contain alphanumeric character and `_`.
They may not start with a number. There's no real standard on how variable names should be written but to
distinguish them from other names, it's useful to write them in all lowercase and with underscores:

```php
$my_new_variable = 42;
```

Every variable in PHP has a certain "visibility". Variables that are defined inside a function are only visible
inside that function. Also variables from outside of a function are not visible on the inside.

```php
<?php

$foo = 23;

function test()
{
    $foo = 42;
    echo $foo . PHP_EOL; //will print "42"
}

echo $foo . PHP_EOL;     //will print "23" 

test();
```
  
The `$foo` variable inside the function is not the same a the one on the outside. The function
creates a clear separation of those two scopes. That means, you can use variable names inside of functions without worrying,
if they are already used somewhere else.

## Constants

Constants are containers for values, like variables, but they can only be set once.
Their names are written in all upper case, alphanumeric characters and underscores.

`define()` is used to set a constant:

```php
define("MY_CONSTANT", 42);
var_dump(MY_CONSTANT); //prints int(42)
```

PHP has a lot of builtin constants, one of them is `PHP_EOL` which contains a string with a line ending.

On some operating systems, line endings are different. By using `PHP_EOL` you can make sure to always use the correct one
for the OS your program is running on.

## Primitive Types

Every value in PHP has a type. There are eight different basic types, also called "primitive" types.

Here are a few of them:

### Boolean

```php
var_dump(true);  //prints bool(true)
var_dump(false); //prints bool(false)
```

Boolean values are either `true`or `false`. They are used for simple yes/no decisions.

For example, the comparison operators like `===`, `>`, `<=`, etc. return boolean values.


### Integer

```php
var_dump(42); //prints int(42)
```

Integers are whole numbers, positive and negative.

### Floating Point Number

```php
var_dump(23.5); //prints float(23.5)
```

"Floats" are numbers with decimal fractions, also positive and negative.

### String

```php
var_dump("foobar"); //prints string(6) "foobar"
var_dump('derp');   //prints string(4) "derp"
``` 
 
A string is a list of characters that usually form a piece of text.

### Array

```php
$foo = [1,2,3];

var_dump($foo);

/* prints:
array(3) {
 [0]=>
 int(1)
 [1]=>
 int(2)
 [2]=>
 int(3)
}
*/
```

An array in PHP is a list of arbitrary values. Each value has an index that can be used to
adress it:

```php
var_dump($foo[1]); //prints int(2)
```

A new element is added to the end of an array like this:

```php
$foo = [1,2,3];
$foo[] = 4;

var_dump($foo);

/* prints:
array(3) {
 [0]=>
 int(1)
 [1]=>
 int(2)
 [2]=>
 int(3)
 [3]=>
 int(4)
}
*/
```

Arrays also have other uses in PHP, but those will be covered later.

## Operators

Our example code only uses a few operators:

[Arithmetic Operators](http://php.net/manual/en/language.operators.arithmetic.php)
[String Operators](http://php.net/manual/en/language.operators.string.php)
[Comparison Operators](http://php.net/manual/en/language.operators.comparison.php)

There are a lot more but those will be covered in more detail later.

**An important thing about comparison operators:**

In most cases, use the type safe versions `===` and  `!==` to check for (un)equality.
The reason for this is PHP's "type juggling" which we wil dedicate a separate chapter to.

## Control flow

Our example uses on control flow structure: a for-loop.

For-loops are also common on other programming languages and usually have a similar syntax:

```php
for($i = 0; $i < 4; $i++) {
    echo $i . PHP_EOL;
}

/* prints:
0
1
2
3
4
*/
```

In the for loop's head, there are three expressions:

1. `$i = 0`: This is executed once at the start of the loop.
1. `$i < 4`: This is checked before every iteration of the loop. The loop will iterate further, if this is `true`.
1. `$i++`: This is executed after each iteration of the loop.

The code inside the `{}` is called the loop's "body" and is executed on each iteration.

## Complex Expressions

The last line of our example has a more complex expression:

```php
echo implode(fibonacci(10), ", ") . PHP_EOL;
```

The `implode()` function is a built-in function of PHP.
It takes an array and a string as arguments and returns a single string:

```php
echo implode([1,2,3], ", "); //prints 1, 2, 3
echo implode([1,2,3], "/");  //prints 1/2/3
```

So, the last line executes our `fibonacci()` function, which returns an array of 10 integer numbers.

That array is then `implode`d into a comma-separated string and printed with a line ending at the end.

---

That was a quick intro into some of PHP's most common syntax elements.
