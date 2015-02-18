# A Web Application

In this chapter, we will start right away with a web application.
After all, that's what PHP is almost exclusively used for.

## Preparation

You will need a new directory for this. Also, copy over the `composer` file from chapter one
so you can use it here as well.

Change into that new directory and run `./composer init`

First it will ask you for some details:

```
  Welcome to the Composer config generator



This command will guide you through creating your composer.json config.

Package name (<vendor>/<name>) []:
Description []:
Author []:
Minimum Stability []:
License []:
```

You can leave all of those at their default values or leave them blank.
They only matter, if you intend to publish your project.

Then, it will ask, if you want to define your dependencies interactively.

Answer `yes`, search for `"silex"` and pick the one called `silex/silex` from the list,
it should be the top entry with index `0`. Leave the version constraint question blank.

```
Would you like to define your dependencies (require) interactively [yes]?
Search for a package []: silex

Found 15 packages matching silex

   [0] silex/silex
   [1] silex/web-profiler
   [2] silex/ckan_client
   [3] jdesrosiers/silex-cors-provider
   [4] bernard/silex
   [5] mheap/silex-assetic
   [6] gigablah/silex-oauth
   [7] neutron/silex-imagine-provider
   [8] guzzle/silex-provider
   [9] sorien/silex-dbal-profiler
  [10] jasongrimes/silex-simpleuser
  [11] silex/api
  [12] macedigital/silex-jms-serializer
  [13] mheap/silex-memcache
  [14] herrera-io/silex-pdo

Enter package # to add, or the complete package name if it is not listed []: 0
Enter the version constraint to require (or leave blank to use the latest version) []:
Using version ~1.2 for silex/silex
Search for a package []:
Would you like to define your dev dependencies (require-dev) interactively [yes]? no
```

When composer asks for a search term again, just hit ENTER,
and answer the question for dev-dependencies with `no`.

Composer will now ask for a final confirmation of the data it just collected. Just hit ENTER.

```
Do you confirm generation [yes]?
```

Now run `./composer install`. Composer will now download some libraries. This might take a moment.

Afterwards, there should be a new directory called `vendor`. That's where composer puts all dependencies.

## Getting it started

Create a new file, called `index.php` with the following content:

```php
<?php

require "vendor/autoload.php";

$app = new Silex\Application();

$app->get("/", function(){
    return "Hello world!";
});

$app->run();
```

Now run `php -S localhost:8000 index.php` from your terminal
and open `http://localhost:8000/` in your web browser. You should see this:

```
Hello World!
```    

## What just happened?


We just created a web application that runs on your computer and answers every request with "Hello World!".

Using Composer, we downloaded an application framework called "silex". Silex helps us handle web requests
without needing to do much. So, how does it work?

First, we need to tell PHP where it can find all the dependencies that we just downloaded.

```php
require "vendor/autoload.php";
```

When we ran `./composer install` it created an `autoload.php` file inside the `vendor` directory.
That file contains instructions for PHP how to find Silex and all other dependencies.

The `require` keyword is used to load another PHP file into our program. After that, PHP knows where to find everything.

Next, we create an `Application` object. PHP is also an object oriented language and it uses a
class based object system, like Java or Ruby. Classes are organized into "namespaces" to prevent multiple
classes from having the same name. Silex has it's own namespace `Silex` and to get the `Application` class from that
namespace we use `Silex\Application`. Nested namespaces are separated with a `\` in PHP.

To make a new object from a class, we use the `new` keyword:

```php
$app = new Silex\Application();
```

The `()` at the end indicate that this is in fact a function call and that it can accept arguments. This one doesn't, though.

Now we tell our Silex application what URLs we want it to respond to. Our example only uses the `/` URL so far.

```php
$app->get("/", function(){
    return "Hello world!";
});
```

This is a method call. Methods are functions that belong to an object. In this case `get()` is a method of `$app`.
PHP uses the `->` operator to address methods and properties of objects.

This `get()` method accepts two arguments: The URL that our app should respond to and a function. Functions in PHP can
also be used anonymously. Now, when a user sends a request for `/` to our application, it will execute this anonymous function
and respond with whatever that function returned.

In this case, it just always returns `"Hello World!"`. Silex takes care of sending that response back to the browser for us.

Finally, after we have set up our application, we launch it with another method call `$app->run()`
and Silex handles everything from there.
