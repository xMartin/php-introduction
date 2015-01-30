# Type Juggling

PHP is a dynamically typed languuage, like Ruby, Python or JavaScript. That means that a variable may contain a value of any type. The type of a variable is nit known until the program actually executes.

The alternative approach to that are statically typed languages where the type of a variable is defined explicitly in the code or inferred by the compiler before the program is executed. Because in statically typed languages the type of most values is already known, they can enforce that only compatiple operations are performed on those values.

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

Now, the comparison in `42 < 0` which is `false`. That's why the `echo` wasn't executed.
