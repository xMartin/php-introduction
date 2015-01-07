# Objects and Classes

Modern PHP relies heavily on an object oriented style of structuring code. You'll find
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

/* prints:

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
    */
```

The output tells us, it is an `object` of the class `Address` and it has 5 properties. The `#1`
is an internal ID that PHP assigns to every object to keep track of them.

Now, every property of our object is `NULL` which is PHP's special value for "there's nothing here".
