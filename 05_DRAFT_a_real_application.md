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

## Data

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
  
If you wonder, what that gibberish text is, it's called ["Lorem Ipsum"](http://en.wikipedia.org/wiki/Lorem_ipsum). It's used as a palceholder because I didn't come up with something more creative.

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

## YAML

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

This will put the `Yaml` class from the `Symfony\Component\Yaml` namespace directly into our current namespace so we can access it just by its name. This `use` statement should go right after the `require` in line 3. It's usually a good idea to put all `use` declarations at the top of your files so it's immediately obvious what dependencies a file needs.

We will need to read our events data at more than one place in our app so let's create a function the does that for us. That way, we avoid writing the same code twice.

```php
function get_events() {
    $yaml_data = Yaml::parse(file_get_contents('events.yml'));
    $events = array_map(function($event) {
        $event['date'] = new DateTime($event['date']);
        return $event;
    }, $yaml_data);
    return $events;
}
```

Now let's see what we did here. `Yaml::parse()` is a so called "static method" of the class `Yaml`. Static methods can be called without making an instance of the class first. They are directly callable on the class itself. Thsi method takes a string as an argument and tries to parse it as YAML. It then returns the result.

We get the YAML string from the `events.yml` file using `file_get_contents()`, it's a builtin function of PHP that reads a text file into a string. For a small file like ours, it's perfectly fine. For large files, it can be probelmatic because it loads the entire file into memory at once.

## Date

Our events have a date but in our YAML files, the dates are just strings like "2015-01-20". We will later do some date/time operations with the so we need to convert them into actual dates. PHP has the `DateTime` class for this which represents a point in time and enables many time-related operations. We can contruct one of those by using the string representation as an argument for the constructor:

```php
$date = new DateTime('2015-01-20');
var_dump($date);
```

This will print:

```
object(DateTime)#1 (3) {
  ["date"]=>
  string(26) "2012-01-20 00:00:00.000000"
  ["timezone_type"]=>
  int(3)
  ["timezone"]=>
  string(13) "Europe/Berlin"
}
```

Note that the output contains a timezone. PHP always needs to know what timezone it should use for time-related operations. The default timezone is either configured for the entire PHP installation or it can be set by the application itself. We will set it ourselves. To make our app usable in different timezones, we put the timezone into a configration file instead of hard-coding it into our PHP code. Let's call it `config.yml` and put this into it:

```yml
---
timezone: "Europe/Berlin"
date_format: "d.m.Y"
time_format: "H:i"
```

The other two entries, beside the timezone, will become important soon.

To actually use this configuration, we need to parse this YAML file and put the config values somwhere where we can access it conveniently throughout our application. We'll use constants for that:

```php
$config = Yaml::parse(file_get_contents('config.yml'));
define("TIMEZONE", $config['timezone']);
define("DATE_FORMAT", $config['date_format']);
define("TIME_FORMAT", $config['time_format']);
```

Now we have a constant for each config value and we can actually use them:

```php
date_default_timezone_set(TIMEZONE);
```

PHP provides the `date_default_timezone_set()` function to set the timezone for your application. If your app does anything with date or time, you need to pay attention to this setting.

## Mapping Arrays

In our `get_events()` function, we use another new thing: `array_map()`. After parsing our `events.yml` file, we get an array of our events and we need to convert the date string into a real date for each of them. `array_map()` iterates over an array, executes a custom function for each element and returns a new array with the return values of those function calls:

```php

$arr = [1,2,3,4];

$arr2 = array_map(function($item) {
    return $item * 2;
}, $arr);

var_dump($arr2);
```

This will print:

```
array(4) {
  [0]=>
  int(2)
  [1]=>
  int(4)
  [2]=>
  int(6)
  [3]=>
  int(8)
}
```

Here, we doubled every element of the array. We used the same approach to make a new array of events with real dates.
