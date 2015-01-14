# A real application

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

## Listing events

Ok, we did some inital setup, no we'll see, if that worked. Here's our first URL handler for the home page of our app:

```php
$app->get('/', function() use ($app) {
    $events = get_events();
    
    usort($events, function($a, $b) {
        if ($a < $b) {
            return -1;
        } else if ($a > $b) {
            return 1;
        } else {
            return 0;
        }
    });
    
    return var_export($events, true);
});
```

We use our `get_events()` function to load the event data. After that, we need to make sure that they are sorted by date properly. For that, we use `usort()`, PHP's sorting function that accepts a custom comparison function. PHP doesn't know how to sort our custom event data by date, so we have to supply a function that tells it when an event is "less than" or "greater than" another event. The convention for that is, that our function returns `-1` if the first value is considered "smaller", `0` when they are equal and `1` when the first value is "less than" the second. This way, we can make any values sortable by our own criteria.

After sorting, we use `var_export()` to get a quick look at our data. `var_export()` is similar to `var_dump()` but it returns its output instead of printing it, if we set the second argument to `true`. We will use something much better for output in a moment but to check, if everything so far works, this is ok.

Start the app with `php -S localhost:8000 app.php` and open `http://localhost:8000/` in your browser. The output will be hard to read. Look at the source code of the page in your browser (Usually right-click->"show source", or something like that). Now you'll see something much like a `var_dump()` output.

## Twig

Displaying data in such a raw form is barely useful, except for debugging purposes. We'll need to generate some HTML to make a real web page. Instead of buidling the HTML output ourselves, we will use a very powerful templating language called [Twig](http://twig.sensiolabs.org).

Make a new directory in your project called `views` and create a file called `event_list.twig` with the following content:

```twig
<!doctype html>
<html>
    <body>
        <ul>
            {% for event in events %}
            <li>
                <a href="/{{event.id}}">
                <h2>{{event.title}}</h2>
                <p>{{event.date | date(date_format)}} - {{event.date | date(time_format)}}
                </a>
            </li>
            {% endfor %}
        </ul>
    </body>
</html>
```

Now, our Silex application needs to know that we intend to use Twig:

```php
$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/views'
]);
```

Silex already has a component for using Twig, the `TwigServiceProvider`. When we instantiate it we just ned to tell it where it can find our templates. It uses an array for its configuration and the `twig.path` value must contain a directory path where the templates are stored. We use the "magic constant" `__DIR__` to get the directory where `app.php` is and then append `/views` to get the full path to our views directory.

We can now replace the `var_export()` line in our URL handler with something else:

```php
    //return var_export($events, true);
    
    return $app['twig']->render('event_list.twig', [
        'events' => $events,
        'date_format' => DATE_FORMAT,
        'time_format' => TIME_FORMAT
    ]);
 ```
 
 Here's where the other two contant come into play. We apss their values into our template so we can use the configured date/time formats in Twig.
 
 By registering the `TwigServiceProvider` we now have a Service for rendering Twig templates in `$app['twig']`. When we call its `render()` method with a template name and the data for that template, it returns the rendered HTML result.
 
Go ahead, refresh your browser window. You'll now see an HTML list of your events. Each of the list items is a link and when you click it ... it results in an error. Don't worry, that's expected. We just havent implemented the detail page for individual events. Let's do that now:

Add this to your app.php file, after the first URL handler:

```php
$app->get('{id}', function($id) use ($app) {
    $events = get_events();
    foreach($events as $e) {
        if ($e['id'] === $id) {
            $event = $e;
            break;
        }
    }
    if (!$event) {
        $app->abort(404, "Event '$id' does not exist.");
    }
    return $app['twig']->render('event_details.twig', [
        'event' => $event,
        'date_format' => DATE_FORMAT,
        'time_format' => TIME_FORMAT
    ]);
});
```

Also, make a new Twig template called `event_details.twig`:

```twig
<!doctype html>
<html>
    <body>
        <h1>{{event.title}}</h1>
        <p>{{event.date | date(date_format)}}
        <p>{{event.date | date(time_format)}}
        <p>{{event.description}}</p>
    </body>
</html>
```

This URL handler function is different from the first: It has a URL parameter `{id}`. That means, it will take that part of the URL and pass it into our function as an argument. And since all of our events have a unique ID, we can find the right one by comparing that parameter with their IDs:

```php
foreach($events as $e) {
    if ($e['id'] === $id) {
        $event = $e;
        break;
    }
}
 ```

This is a `foreach` loop, it iterates over an array and puts the current element into the variable `$e` (or whatever else you name it) everytime it runs. Insode the loop, we check, if the current event's ID is the one we got as a URL parameter. If they match, we put that event into the `$event`variable and stop the loop with `break;`.

There's the possibility that our loop didn't find any matching event. In that case the `$event` variable will be empty and we can react to that by telling Silex to produce an error page:

```php
if (!$event) {
    $app->abort(404, "Event '$id' does not exist.");
}
```

This works, because PHP treats a value of `null` like `false` when we use it in a condition.

However, if we have found the correct event, we render the new Twig template with it:

```php
return $app['twig']->render('event_details.twig', [
    'event' => $event,
    'date_format' => DATE_FORMAT,
    'time_format' => TIME_FORMAT
]);
```

Again, we also supply the two date/time format constants, just like before. Now, when you reload the page in your browser, the links will work!

---

You now have a simple but already useful PHP aplication. Instead of writing HTML manually for this event calendar, you can edit the data in the `events.yml` file, which is far more convenient.
