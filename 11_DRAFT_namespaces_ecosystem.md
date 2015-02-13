# Namespaces and Ecosystem

There's more to a programming language than just the syntax and semnatics of the language itself. There's usually also a builtin library for the most common tasks and often countless third-party libraries that you can build upon. We already coverd most of PHP's language core and some of the [included library of functions and classes](06_builtin_functions_and_classes.md).

In the past, adding more libraries to your PHP project involved copying their code into your project and keeping trac of everything yourself but those days are over! Now there's [Composer](https://getcomposer.org) which we already used to install the Silex framework in our previous examples.

Composer not only managed your dependencies for your, it also gives aou an easy way to make your own code available throughout your project. It's common practice now to have a `src` directory that contains all actual source code files of your application. Further, you should put every class in its own file, named like the class with `.php` at the end. Now this is where namespaces come in.

## Namespaces

So far our classes and function have all been floating around in our applications, available by just their names. In a complex project this can quickly become a problem, especially, if you have mulitple classes that do similar things and have similar names. It can also lead to name collisions with third party code from libraries. Imagine you have a class called `Address` and some library also has a class with that name. How should PHP know which one to use?

To avoid name collision and to organize our code better, we can put classes and functions into namespaces:

```php
<?php
//filename: src/MyProject/Foo/Derp.php

namespace "MyProject\Foo";

class Derp
{
  //...
}
```

Namespaces are kind of like directories for your code and they usually directly map to actual file directories, like this `MyProject\Foo\Derp" class that lives inside the file `src/MyProject/Foo/Derp.php`. Even the syntax is similar, namespaces just use a `\` as a separator instead of `/`.

Namespaces apply to the entire PHP file that contains the `namespace` declaration. All code in that file now is located in the `MyProject\Foo` namespace.

Notice that there's no `src` namespace although we have a `src` directory. That's because the `src` directory contains the "global" namespace, the top level of all namespaces. Well, it doesn't yet, but Composer will fix that for us. For every project, we should have a `composer.json` file. Composer can help us creating it with `composer init`:

```
Package name (<vendor>/<name>) [lnwdr/my-project]:
Description []:
Author [Leon Weidauer <leon@lnwdr.de>]:
Minimum Stability []:
License []:

Define your dependencies.

Would you like to define your dependencies (require) interactively [yes]? no
Would you like to define your dev dependencies (require-dev) interactively [yes]? no

{
    "name": "lnwdr/my-project",
    "authors": [
        {
            "name": "Leon Weidauer",
            "email": "leon@lnwdr.de"
        }
    ],
    "require": {}
}

Do you confirm generation [yes]? yes
```

For a basic project you can leave most of the questions unanswered or use the default answers. No, let's see what's inside `composer.json`:

```json
{
    "name": "lnwdr/my-project",
    "authors": [
        {
            "name": "Leon Weidauer",
            "email": "leon@lnwdr.de"
        }
    ],
    "require": {}
}
```

The easiest way to make our `src` directory known to composer as the "place for everything" is to add this "autoload" section to `composer.json`:
```json
{
    "name": "lnwdr/my-project",
    "authors": [
        {
            "name": "Leon Weidauer",
            "email": "leon@lnwdr.de"
        }
    ],
    "require": {},
    "autoload": {
        "psr-4": { "": "src/" }
    }
}
```

This tells Composer to use the `src` directory to find all classes that it doesn't already know using the ["PSR-4"](http://www.php-fig.org/psr/psr-4/) standard. The details of that standard are not that important right now. Basically it says: "inside this directory, all namespaces are represented by subdirectories and classes are `<ClassName>.php` files."

*__Autoloading__ is PHP's way to autamatically find classes and their corresponding files without having to `require` all of them manually. You can read more about it [here](http://php.net/manual/en/language.oop5.autoload.php). Autoloading is also one of the reasons why PHP code is usually organized in classes because PHP only supports autoloading for classes, not simple functions for example.*

The only thing we still need to do is to add Composer's autoloading to our application [like we already did in our Silex examples](03_a_web_application.md):

```php
<?php

require "vendor/autoload.php";
```

This should be right on top of your main application file to make sure, all classes can be found right from the beginning. It loads a PHP file that Composer created automatically (`vendor/autoload.php`). This file loads Composer's autoloading logic into our project. After that all third party namespaces from our dependencies as well as our own are avaliable in our code.

### Using Namespaces

To use something from a different namespace we have to `use` it:

```php

<?php

<?php
//filename: app.php

use MyProject\Foo;

$d = new Derp();
```

`use` pulls in a nother namespace into the current scope and makes everything in that namespace available. It also serves as a kind of documentation about what other classes a particular PHP file will require so it's good to have all `use` statements well organized at the top of our PHP files.
