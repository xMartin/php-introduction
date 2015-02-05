# More on functions

We already covered the basics about functions in [basic syntax](02_basic_syntax_example.md#function-definitions).
Now we're going to get into more detail about them.

## Type hinting

Among the popular dynamic languages, PHP has an unusual feature. It's called ["type hinting"][type_hinting].
In PHP, functions have s limited way of declaring the type of their expected arguments.
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

print_array("this is a string"); //results in a "Fatal Error", PHP does not allow this call and stops the program
```

Violations of type hints result in "Catchable fatal errors" and terminate the entire program, if they're not caught.
These errors can be caught like other exceptions. We will cover exception handling in a later chapter.

This ensures that a function can only be called with arguments of the correct type as long as the type is `array` or a class.
Because of this, it's often beneficial in PHP to wrap primitive values into objects so that they can be type hinted.

Type hints can prevent many of the [issues caused by PHP's type system](08_type_juggling.md) introduced in the previous chapter.

## Default values for arguments

Sometimes a function doesn't need all of it's arguments for every call. It can be useful to provide default values for them.
That way, a function may be used more conveniently wothout having to specify all arguments.

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

[type_hinting]: http://php.net/manual/en/language.oop5.typehinting.php
