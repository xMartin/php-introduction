# Type Juggling And Comparisons

**This chapter is especially important for using PHP in general, read it carefully.**

PHP is a dynamically typed language, like Ruby, Python or JavaScript. That means that a variable may contain a value of any type. The type of a variable is not known until the program actually executes.

The alternative approach to that are statically typed languages where the type of a variable is defined explicitly in the code or inferred by the compiler before the program is executed. Because in statically typed languages the type of most values is already known, they can enforce that only compatible operations are performed on those values.

Take this Java program for example:

```java
public class TypeDemo 
{
    public static void main (String[] args)
    {
        int i  = 42;
        String s = "derp";

        if (i < s) {
            System.out.println("42 is less than 'derp'.");
        }
    }
}
```

The important part here is:

```java
int i  = 42;
String s = "derp";

if (i < s) {
    System.out.println("42 is less than 'derp'.");
}
```

The `javac` compiler will complain when we try to compile this because it already knows that `i` will be an integer and `s` will be a string.

```
TypeDemo.java:8: error: bad operand types for binary operator '<'
        if (i < s) {
              ^
  first type:  int
  second type: String
1 error
```

Comparing an integer to a string doesn't make sense in Java. There are pretty strong arguments that it doesn't make sense anywhere.

Even some dynamic languages refuse to execute code like this. Here's the same example in Ruby:

```rb
i = 42;
s = "derp";

if i < s
  puts "42 is less than 'derp'"
end
```

Executing this leads to an exception because as soon as Ruby encounters the comparison operator and realizes that the types don't match, it treats that as a problem:

```
typedemo.rb:4:in `<': comparison of Fixnum with String failed (ArgumentError)
	from typedemo.rb:4:in `<main>'
```

Let's do the same in PHP:

```php
$i = 42;
$s = 'derp';

if ($i < $s) {
    echo "42 is less than 'derp'";
}
```

When we execute this program, no output is printed. But why?

PHP didn't complain about comparing two incompatible types. It did something to the `'derp'` value in `$s`. When a value in PHP is used in a context that it normally wouldn't fit, PHP will try to convert the value into a type that is compatible with the attempted operation. In this case, we try to compare a string to an integer. PHP converts both values into integers to make the comparison possible. Since `'derp'` contains no digits that could be parsed as a number, PHP converts in into `0`.

Now, the comparison in `42 < 0` which is `false`. That's why the `echo` line wasn't executed.

**This behaviour, called "type juggling", "type coercion" or "implicit typecasting" is one of the major sources of bugs, security flaws and code maintainability issues in PHP. It's usually a good idea to avoid it.**

## Avoiding type juggling

### Type safe equality

Most operators in PHP can cause type juggling. The main exception is `===`, the type safe equality operator. It returns `true` if both values are of the same type and the same value and it doesn't try to convert them into anything. The `==` only checks for equal values AFTER implicit type casting.

**When checking two non-object values for equality, always use `===`.**


Not only does it prevent hard to predict behaviour it also makes it clear that you want to check for real equality in your code.

There are no type safe equivalents for the other comaprison operators, like `<`, `>`, `<=` and `>=`. They always cause implicit type casting, somtimes in unexpected ways. For example, it can happen, that two strings are cast into intgers or float when compared with these operators. In most cases, it's only safe to use them with integers. Floats are problematic for comparison operatins due to their inherent imprecision.

### Comparing objects

For objects, the comparison operators behave differently. `==` will return true, if both values are objects of the same class and have the same values in their properties. The properties are compared using `==` again.

For objects, `===` will only return `true`, if both values are the exact same object.

```php
<?php

class Foo
{
    protected $str;

    public function __construct($str)
    {
        $this->str = $str;
    }
}

$a = new Foo("42");
$b = new Foo(42);
$c = $a;

var_dump($a === $b); //prints "false", $a and $b contain different objects
var_dump($a == $b);  //prints "true", $a and $b are of the same class and have equal properties
var_dump($a === $c);  //prints "true", $a and $c contain the exact same object
```

The non-equal comparison operators, `<`, `>`, `<=` and `>=`, are [practically unusable and undocumented on objects](http://php.net/manual/en/language.oop5.object-comparison.php#98725).

### Comparing strings

When comparing strings, it can happen that PHP converts both values of a comparison into integers or floats. To avoid that, use the [`strcmp`](http://php.net/manual/en/function.strcmp.php) function.

### Explicit typecasting

An alternative to letting PHP do implicit typecasting is to explicitly do it yourself. In PHP, there's a special syntax for converting types:

```php
<?php

$i = 42;
$f = 3.14;
$s = "derp"
$b = false;

var_dump( (string)$i ); //prints 'string(2) "42"'
var_dump( (int)$f );    //prints 'int(3)'
var_dump( (bool)$s );   //prints 'bool(true)'
var_dump( (float)$b );  //prints 'float(0)'
```

Prefixing a value with a type in parentheses forces the value to be cast into that type, whether it makes sense or not. The advantage in doing so is that it's now obvious what your code does and the resulting behaviour becomes easier to understand and more predictable. This will make it easier to spot and fix problems. Let's change our example from before accordingly:

```php
<?php

$i = 42;
$s = "derp";

if ((string)$i < $s) {
    echo "'42' is less than 'derp'";
}
```

We now convert the integer `42` into the string `'42'` before comparing it with `'derp'`. Instead of comparing `42` to `0`, as before, we now compare two strings and as a string comparison, the result actually makes sense: `'4'` comes before `'d'`. So, if you would have used this comparison to sort a list of strings alphanumerically, the result would now be correct.

## Guidelines

* for primitive values, use `===` to check for equality and `!==` for inequality
* use specific comparison functions like `strcmp()`, if possible.
* be aware of implicit type casting when using any other comparison operator
* for objects, `===` checks, if two values are the same object
  * other operators are not very useful on objects.
  * you should implement specific methods for comparisons between objects.
* use explicit type casting to make your intent clear in your code, if it's ambiguous
* consult the manual frequently

## Further Reading

Sadly, there are no simple, universal rules for dealing with the behaviour of types in PHP. There are just too many exceptions and unexpected cases. That's why it's important to be aware of this problem and refer to the manual when in doubt.  The PHP manual has a pretty detailed chapters on [type juggling](http://php.net/manual/en/language.types.type-juggling.php), [comparison operators](http://php.net/language.operators.comparison) and [extensive tables on how values compare to each other](http://php.net/manual/en/types.comparisons.php). It's good to remember where to find these.
