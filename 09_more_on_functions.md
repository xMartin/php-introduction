# More on functions

We already covered the basics about functions in [basic syntax](02_basic_syntax_example.md#function-definitions).
Now we're going to get into more detail about them.

## Type hinting

Among the popular dynamic languages, PHP has an unusual feature. It's called ["type hinting"](http://php.net/manual/en/language.oop5.typehinting.php).
In PHP, functions have a limited way of declaring the type of their expected arguments.
If an argument is supposed to be an `array` or an instance of a specific class, we can make sure that these requirements are fulfilled.

```php
<?php

function print_array(array $values)
{
    foreach($values as $value) {
        echo $value . PHP_EOL;
    }
}

class Foo
{
    protected $name;
    
    public function __construct($name) {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }
}

function print_foo(Foo $f)
{
    echo $f->getName();
}

$a = [1, 2, 3, 4];
$b = new Foo("some_name");

print_array($a);
/* prints:
1
2
3
4
*/

print_foo($b); //prints "some_name"

print_array("this is a string"); //terminates with a "Fatal Error"
```

Violations of type hints result in "Catchable fatal errors" and terminate the entire program, if they're not caught.
These errors can be caught like other exceptions. We will cover exception handling in a later chapter.

This ensures that a function can only be called with arguments of the correct type as long as the type is `array` or a class.
Because of this, it's often beneficial in PHP to wrap primitive values into objects so that they can be type hinted.

Type hints can prevent many of the [issues caused by PHP's type system](08_type_juggling.md) introduced in the previous chapter.

## Default values for arguments

Sometimes a function doesn't need all of it's arguments for every call. It can be useful to provide default values for them.
That way, a function may be used more conveniently without having to specify all arguments.

```php
<?php

function print_array(array $values, $reverse = false)
{
    if ($reverse) {
        $print_values = array_reverse($values);
    } else {
        $print_values = $values;
    }
    
    foreach ($print_values as $value) {
        echo $value . PHP_EOL;
    }
}

print_array([1, 2, 3, 4]);
/* prints:
1
2
3
4
*/


print_array([1, 2, 3, 4], true);
/* prints:
4
3
2
1
*/
```

## Pass by reference

Usually, function arguments are passed "by value". That means, the content of a variable is copied into the functions argument variable when it is called. As a consequence, function can't modify the contents of the variables where their arguments came from:

```php
<?php

$foo = 42;

function bar($n)
{
    $n = n + 1;
    return $n;
}
echo $foo . PHP_EOL;      // prints "42"
echo bar($foo) . PHP_EOL; // prints "43"
echo $foo . PHP_EOL;      // still prints "42"
```

The value `42` was copied into the function `bar()` and only that copy was modified into `43`. The value inside of `$foo` was not changed.

Sometimes, it is necessary to circumvent this. You can pass a variable into  a function "by reference". That means, you give the actual variable, not a copy of its value, into a function so the function can modify it. Arguments that are passed "by reference" are marked with an `&`:

```php
<?php

$foo = 42;

function bar(&$n)
{
    $n = n + 1;
    return $n;
}
echo $foo . PHP_EOL;      // prints "42"
echo bar($foo) . PHP_EOL; // prints "43"
echo $foo . PHP_EOL;      // also prints "43"
```

Now, the value in `$foo` has changed because it wasn't copied as it was passed into the function `bar()`.

Some of PHP's built-in functions have "by reference" arguments, for example [`sort()`](http://php.net/manual/de/function.sort.php), [`reset()`](http://php.net/manual/de/function.reset.php) or [`shuffle()`](http://php.net/manual/de/function.shuffle.php).

Usually it is not necessary to use "pass by reference" and it's often even dangerous as functions suddenly have side effects that can be hard to predict but there are situations where it is useful. One of them is described later in this chapter.

**Important note: Objects are ALWAYS passed by reference! They are not automatically copied when passed as arguments!**

## Anonymous functions

When we built that small application with Silex, we already saw a slightly different function syntax:

```php
<?php

require "vendor/autoload.php";

$app = new Silex\Application();

$app->get("/", function(){
    return "Hello world!";
});

$app->run();
```

This piece of code contains a special kind of function called an [anonymous function](http://php.net/manual/en/functions.anonymous.php):

```php
$app->get("/", function(){
    return "Hello world!";
});
```

Here, a function is created and used as an argument for the `$app->get()` function. Unlike "normal" PHP functions, it doesn't have a name. It's just a value that can be stored in a variable, passed into another function or be returned by a function. Functions that can be used like any other value are also called "higher order functions" or "first class functions".

A common usage for them is sorting. Let's sort an array of people's names by their last name, something the built in sorting functions can't do:

```php
<?php

$people = [
    "Marie Curie",
    "Alan Turing",
    "Grace Hopper",
    "Ada Lovelace",
    "Albert Einstein"
];

usort($people, function($a, $b) {
    $a_array = explode(' ', $a);
    $b_array = explode(' ', $b);
    $a_last_name = end($a_array);
    $b_last_name = end($b_array);
    
    return strcmp($a_last_name, $b_last_name);
});

var_dump($people);
/* prints:
array(5) {
  [0]=>
  string(11) "Marie Curie"
  [1]=>
  string(15) "Albert Einstein"
  [2]=>
  string(12) "Grace Hopper"
  [3]=>
  string(12) "Ada Lovelace"
  [4]=>
  string(11) "Alan Turing"
}
*/
```

Here we use the [`usort()`](http://php.net/manual/en/function.usort.php) function that uses a custom function to compare values of an array in order to sort them. We then use an anonymous function that extracts the last names from the two arguments and passes them to [`strcmp()`](http://php.net/manual/en/function.strcmp.php), returning the result.

Anonymous functions are a good way to inject behavior and logic into other functions or objects from the outside.

## Closures

PHP's anonymous functions have another feature. They can behave as closures. A closure is a function that has access to the scope in which is was originally defined while normal functions can only access their own arguments and variables that were created inside the function.

```php

$foo = 42;

$f = function($x) use ($foo) {
    return $x * $foo;
}

echo $f(2) . PHP_EOL; // prints: "84"
```

Unlike in other languages, we have to explicitly declare what variable the closure may access.

Let's say, we need a function that produces automatically increasing numbers. This can be implemented using a closure:

```php
<?php

function get_incrementor()
{
    $i = 0;

    return function() use (&$i)
    {
        return $i++;
    };
}

$incrementor = get_incrementor();

echo $incrementor() . PHP_EOL;  // prints "0"
echo $incrementor() . PHP_EOL;  // prints "1"
echo $incrementor() . PHP_EOL;  // prints "2"
echo $incrementor() . PHP_EOL;  // prints "3"
echo $incrementor() . PHP_EOL;  // prints "4"

$incrementor2 = get_incrementor();
echo $incrementor2() . PHP_EOL; // prints "0"
echo $incrementor2() . PHP_EOL; // prints "1"

echo $incrementor() . PHP_EOL;  // prints "5"
```

Here we use a function that returns another function, one of the features of anonymous functions. But we also declare the variable `$i` and pass it into this anonymous function with the `use ()` syntax. We pass it "by reference" because it must be modified when the anonymous function is called.

Now this anonymous function is also a closure with access to `$i` and it can modify the variable `$i` because we passed it "by reference".

Every time the closure is called, it will return the value of `$i`and increment it by one. And by calling `get_incrementor()` again, we can get a new closure that starts at `0` again while the original one still continues its sequence as expected.


## Argument list of arbitrary length

Functions can be called with more arguments than they accept. Normally they just ignore those unknown arguments.

```php
<?php

function foo($a, $b)
{
    echo $a . ' ' . $b . PHP_EOL;
}

foo(1, 2, 3, 4); // prints "1 2"
```

But there are ways to access these excess arguments. One of them is the "variadic function" syntax that was introduced in PHP 5.6:

```php
function bar($a, $b, ...$args) {
    echo $a . ' ' . $b . ' ' . implode('/', $args) . PHP_EOL;
}

bar(1, 2, 3, 4, 5); //prints "1 2 3/4/5"
```

This syntax is quite new and maybe not available on every PHP installation but there's another way to do this, using [`func_get_args()`](http://php.net/manual/en/function.func-get-args.php) and [`func_get_arg()`](http://php.net/manual/en/function.func-get-arg.php):

```php
function bar() {
    $a = func_get_arg(0);
    $b = func_get_arg(1);
    $args = array_slice(func_get_args(), 2);

    echo $a . ' ' . $b . ' ' . implode('/', $args) . PHP_EOL;
}

bar(1, 2, 3, 4, 5); //prints "1 2 3/4/5"
```
