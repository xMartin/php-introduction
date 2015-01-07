# Objects and Classes

Modern PHP relies heavily on an object oriented style for structuring code. You'll find
this style in many libraries, frameworks and projects.

## What is an object?

In PHP `object` is one of the primitive types. It describes a value that is a bundle of data
and often also logic to work on that data.

An object lets you handle complex data structures as single values. Let's take an address for example.
It consists of several pieces of data:

* street
* house number
* city
* postal code
* country

With the simpler primitive types, we can't store an address in a meaningful way so we need a
data structure to store an address in one single value to make sure, we keep track of every piece of data that belongs to it.

We also ned to make sure that all addresses have the same structure, so that the parts of our application that handle addresses
can rely on. For example, the `postal_code` property of an address should always have the same name. If it's called `zip_code`
sometimes, it leads to inconsistency and potentially dangerous bugs.

To ensure a certain object structure, we use "class", a kind of blueprint for an object.

## Classes

```php
<?php

class Address
{
    public $street;
    public $house_number;
    public $city;
    public $postal_code;
    public $country;
}
```

This is a class definition. It introduces a class called `Address` and defines that all objects of this class have
the same five properties that we need to store an address.

Class names are written in a style called `StudlyCaps`: like `MyAwesomeClass`.

To make an address object from this class, we use the `new` operator:

```php
$my_address = new Address();

var_dump($my_address);
```

Output:

```
object(Address)#1 (5) {
  ["street"]=>
  NULL
  ["house_number"]=>
  NULL
  ["city"]=>
  NULL
  ["postal_code"]=>
  NULL
  ["country"]=>
  NULL
}
```

The output tells us, it is an `object` of the class `Address` and it has 5 properties. The `#1`
is an internal ID that PHP assigns to every object to keep track of them.

### Properties

Now, every property of our object is `NULL` which is PHP's special value for "there's nothing here". To fill this address value with actual data, we could set every single property individually:

```php
$my_address->street = "Main Street";
$my_address->house_number = 42;
$my_address->city = "Some Town";
$my_address->postal_code = "12345";
$my_address->country = "Far Far Away";
```

Output:

```
object(Address)#1 (5) {
  ["street"]=>
  string(11) "Main Street"
  ["house_number"]=>
  int(42)
  ["city"]=>
  string(9) "Some Town"
  ["postal_code"]=>
  int(12345)
  ["country"]=>
  string(7) "country"
}
```

To access a property of an object, PHP uses the `->` operator, so `$my_address->street` means "the `street` property of the object in the variable `$my_address`". 

### Methods

But setting every single property is tedious and not very easy to read. What, if we could set it all at once? We'll need to add something to the class to do that.

```php
<?php

class Address
{
    public $street;
    public $house_number;
    public $city;
    public $postal_code;
    public $country;

    public function set($street, $house_number, $city, $postal_code, $country)
    {
        $this->street = $street;
        $this->house_number = $house_number;
        $this->city = $city;
        $this->postal_code = $postal_code;
        $this->country = country;
    }
}

$my_address = new Address();

$my_address->set("Main Street", 42, "Some Town", 12345, "Far Far Away");

var_dump($my_address);
```

Output:

```
object(Address)#1 (5) {
  ["street"]=>
  string(11) "Main Street"
  ["house_number"]=>
  int(42)
  ["city"]=>
  string(9) "Some Town"
  ["postal_code"]=>
  int(12345)
  ["country"]=>
  string(7) "country"
}
```

What we added, is a method. A function that is added to every object of our class. Inside a method, there's a special variable called `$this`, it refers to the object that the method was called on, in this case, the address object.

The `set` method just copies the values that it got via its arguments to the corresponding object properties.

### Constructors

Making an address object without any data in the first place doesn't really make sense. It would be useful, and simpler to use, if we could set the data right when we create the object. There's a special method that does that, it's called a "contructor".

```php
<?php

class Address
{
    public $street;
    public $house_number;
    public $city;
    public $postal_code;
    public $country;

    public function __construct($street, $house_number, $city, $postal_code, $country)
    {
        $this->street = $street;
        $this->house_number = $house_number;
        $this->city = $city;
        $this->postal_code = $postal_code;
        $this->country = country;
    }
}

$my_address = new Address("Main Street", 42, "Some Town", 12345, "Far Far Away");

var_dump($my_address);
```

The output will remain the same again. But this time, we didn't need to call an extra method to iniitalize our object. If a class has a method with the exact name `__construct`, it will be called when the object is created with the `new` operator. All arguments from the `new`call will also be passed to the contructor.


## Visibility

Right now everything in our `Address` objects is accessible from the oustide. A piece of code that doesn't behave well could to this:

```php
function does_bad_things($address)
{
    $address->city = "Gotham City";
    
    return $address;
}
```

That function would modify an address after it has been created and filled with data. That doesn't make sense, a place doesn't change its address, streets don't magically move to a different city. People can move to a new address but the address itself can't change. We need to prevent the address from being changed after it's created. That's where the concept of "visibility" comes in. You might have wondered, what all the `public` keywords were for until now:

```php
<?php

class Address
{
    protected $street;
    protected $house_number;
    protected $city;
    protected $postal_code;
    protected $country;

    public function __construct($street, $house_number, $city, $postal_code, $country)
    {
        $this->street = $street;
        $this->house_number = $house_number;
        $this->city = $city;
        $this->postal_code = $postal_code;
        $this->country = country;
    }
}
```

Now we changed the visibility of our address' properties to `protected`. The `do_bad_things` function from before will now fail:

```
Fatal error: Cannot access protected property Address::$city in address.php on line 27
```

There are three levels of visibility in PHP: `public`, `protected` and `private`. For now, `public` means "is accessible from outside the object and `protected` means "is not accessible from the outside". We'll go into more detail on this and what `private` means when we talk about inheritance later.
