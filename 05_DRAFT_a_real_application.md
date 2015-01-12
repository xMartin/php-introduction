# DRAFT: A real application

* Silex
* A small event calendar
* data in YAML file
  * list of events, each has:
    * id
    * title
    * date
    * description
    * location (?)
* later add edit-capability

This will be another exercise where we make a little web application. Again we'll use Silex as a framework.
So first, repeat the steps from [chapter 03](03_a_web_application.md) to install Sile into a new directory.
Every project should have its own directory so things don't get messy.

Last time, our application just said "Hello World", now that's nice but not very useful. This time, we'll make an
event calendar, a website that shows events, ordered by date, each witht a title, and description.
Each event will also have it's own page so we could send people a direct link to an event.

First, we need something, to store our events in. To keep it simple, we'll use a text file for that with an easily
human-readable format called "YAML". It will look like this:

```yml
---

- id: "mollis-ornare"
  title: "Mollis Ornare"
  description: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus."
  date: 2015-01-20 20:00
- id: "fusce-euismod-purus"
  title: "Fusce Euismod Purus"
  description: "Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Etiam porta sem malesuada magna mollis euismod. Nullam id dolor id nibh ultricies vehicula ut id elit."
  date: 2015-02-01 19:00
- id: "bendum-odio"
  title: "Bendum odio"
  description: "Donec sed odio dui. Aenean lacinia bibendum nulla sed consectetur."
  date: 2015-02-17 21:00
  ```
  
If you wonder, what that gibberish text is, it's called ["Lorem Ipsum"](http://en.wikipedia.org/wiki/Lorem_ipsum). It's used as a palceholder.

Just save that into a file called `events.yml` in your project's  directory. Change the titles and descriptions, if you like.

Now, let's setup our Silex application. Just like last time, our starting point should look like this:

```php
<?php

require "vendor/autoload.php";

$app = new Silex\Application();

$app->get("/", function(){
    return "...";
});

$app->run();
```

We will need another library to read our YAML file. YAML support is not built into PHP itself. Let's install `symfony/yaml`, a very common library for dealing with YAML:

```sh
./composer require symfony/yaml
```

`composer require` tells Compose  to download one specific dependency and also to add it to our project's `composer.json` file.
If you already know what libraries you want to install, it's quicker than the interactive method we used before.

The things, composer installs for you are called "packages" and each of them has a unique name consisiting of a vendor name and a pakcage name, separated by a `/`,
like `silex/silex` or `symfony/yaml`. These packages are hosted on <https://packagist.org>. There you can get information on each package or search for new packages.

The `symfony/yaml` package provides a namespace `Symfony\Component\Yaml`.
That's a bit too long to type it everytime we want to use something from it. PHP has a way to make the contents of a namespace available to our code more conveniently:
 
```php
use Symfony\Component\Yaml\Yaml;
```

This will put the `Yaml` class from the `Symfony\Component\Yaml` namespace directly into our current namespace so we can access it just with its name. This `use` statement should go right after the `require` in line 3.






